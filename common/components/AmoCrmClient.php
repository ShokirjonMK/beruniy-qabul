<?php

namespace common\components;

use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Models\CustomFields\MultiselectCustomFieldModel;
use \Yii;
use AmoCRM\Models\TagModel;
use yii\helpers\ArrayHelper;
use AmoCRM\Models\LeadModel;
use AmoCRM\Models\ContactModel;
use AmoCRM\Models\CompanyModel;
use common\components\IAmoCrmClient;
use common\components\AmoCrmSettings;
use AmoCRM\Client\AmoCRMApiClient;
use AmoCRM\Models\NoteType\CommonNote;
use AmoCRM\Collections\TagsCollection;
use AmoCRM\Collections\NotesCollection;
use AmoCRM\Helpers\EntityTypesInterface;
use AmoCRM\Collections\ContactsCollection;
use AmoCRM\Collections\CompaniesCollection;
use League\OAuth2\Client\Token\AccessToken;
use yii\base\Component;

class  AmoCrmClient extends Component  implements AmoCrmSettings, IAmoCrmClient
{
    private $apiClient;
    public $clientId;
    public $clientSecret;
    public $redirectUri;
    public $accessToken;
    public $refreshToken;
    public $baseDomain;

    public function init()
    {
        parent::init();

        $this->apiClient = new AmoCRMApiClient(self::CLIENT_ID, self::CLIENT_SECRET, self::REDIRECT_URL);
        $accessToken = new AccessToken([
            'access_token' => self::ACCESS_TOKEN_VALUE,
            'refresh_token' => null,
            'expires' => time() + 3600 * 24 * 365 * 10 // Uzoq muddatli token
        ]);
        $this->apiClient->setAccessToken($accessToken)
            ->setAccountBaseDomain(self::BASE_DOMAIN);
    }
    // get pipelines
    public function getPipelines()
    {
        try {
            $pipelinesService = $this->apiClient->pipelines();
            $pipelines =  $pipelinesService->get();
//            $pipelineArray = ArrayHelper::map($pipelines, 'id', 'name');
            return $pipelines;
        } catch (\AmoCRM\Exceptions\AmoCRMApiException $e) {
            throw new \Exception('Error fetching pipelines: ' . $e->getMessage());
        }
    }

    // pipeline statuses
    public function getPipelineStatuses(int $pipelineId)
    {
        try {
            $pipeline = $this->apiClient->pipelines()->getOne($pipelineId);
            $pipelineStatusesArray = [];
            $pipelineStatuses =  $pipeline->getStatuses();
            foreach($pipelineStatuses as $status){
                // isEditable => true
                if($status->getIsEditable()){
                    $pipelineStatusesArray[$status->getId()] = $status->getName();
                }
            }
            // $pipelineStatusesArray = ArrayHelper::map($pipelineStatuses, 'id', 'name');
            return $pipelineStatusesArray;
        } catch (\AmoCRM\Exceptions\AmoCRMApiException $e) {
            throw new \Exception('Error fetching pipeline statuses: ' . $e->getMessage());
        }
    }
    public function getAccountInfo()
    {
        try {
            $account = $this->apiClient->account()->getCurrent();
            return $account;
        } catch (\AmoCRM\Exceptions\AmoCRMApiException $e) {
            throw new \Exception('Error fetching account info: ' . $e->getMessage());
        }
    }

    public function getUsers()
    {
        try {
            $usersService = $this->apiClient->users();
            $users = $usersService->get();
            return $users;
        } catch (\AmoCRM\Exceptions\AmoCRMApiException $e) {
            throw new \Exception('Error fetching users: ' . $e->getMessage());
        }
    }

    public function getMultiselectCustomFields($entityType = 'leads')
    {
        try {
            $customFieldsService = $this->apiClient->customFields($entityType);
            $customFields = $customFieldsService->get();


            $multiselectFields = [];
            foreach ($customFields as $customField) {
                if ($customField instanceof MultiselectCustomFieldModel) {
                    $multiselectFields[] = $customField;
                }
            }
            // Array id-> name is sort
            $multiselectFields = ArrayHelper::map($multiselectFields[0]->getEnums(), 'id', 'value');
            return $multiselectFields;
        } catch (AmoCRMApiException $e) {
            throw new \Exception('Error fetching custom fields: ' . $e->getMessage());
        }
    }


    public function addLeadToPipeline(string $phoneNumber, string $leadName, string $message, array $tags, array $customFields, int $pipelineId = 0, int $statusId = 0, int $leadPrice = 0)
    {
         try {
            if($pipelineId == 0 ){
                $pipelineId = self::DEFAULT_PIPELINE_ID;
            }
            if($statusId == 0){
                $statusId = self::DEFAULT_STATUS_ID;
            }
            $time = time();
            $newLead = new LeadModel();
            $accountId = $this->getAccountInfo()->getId();
            $newLead
                ->setName($leadName)
                ->setResponsibleUserId(self::DEFAULT_RESPONSIBLE_USER_ID)
                ->setPipelineId($pipelineId)
                ->setStatusId($statusId)
                ->setAccountId($accountId)
                ->setPrice($leadPrice)
                ->setGroupId(0)
                ->setCreatedAt($time)
                ->setUpdatedAt($time);

            $tagsCollection = new TagsCollection();
            foreach ($tags as $tagName) {
                $tag = new TagModel();
                $tag->setName($tagName);
                $tagsCollection->add($tag);
            }
            $newLead->setTags($tagsCollection);

             if (!empty($customFields)) {
                 $customFieldsCollection = $newLead->getCustomFieldsValues() ?: new \AmoCRM\Collections\CustomFieldsValuesCollection();

                 foreach ($customFields as $fieldId => $fieldValue) {
                     $fieldModel = (new \AmoCRM\Models\CustomFieldsValues\TextCustomFieldValuesModel())
                         ->setFieldId($fieldId)
                         ->setValues(
                             (new \AmoCRM\Models\CustomFieldsValues\ValueCollections\TextCustomFieldValueCollection())
                                 ->add(
                                     (new \AmoCRM\Models\CustomFieldsValues\ValueModels\TextCustomFieldValueModel())
                                         ->setValue($fieldValue)
                                 )
                         );
                     $customFieldsCollection->add($fieldModel);
                 }

                 $newLead->setCustomFieldsValues($customFieldsCollection);
             }

             $contact = new ContactModel();
             $contact->setName($leadName); // Set the contact name if needed

             $customFieldsValues = new \AmoCRM\Collections\CustomFieldsValuesCollection();
             $phoneFieldModel = (new \AmoCRM\Models\CustomFieldsValues\MultitextCustomFieldValuesModel())
                 ->setFieldCode('PHONE') // or use setFieldId() if you have the field ID
                 ->setValues(
                     (new \AmoCRM\Models\CustomFieldsValues\ValueCollections\MultitextCustomFieldValueCollection())
                         ->add(
                             (new \AmoCRM\Models\CustomFieldsValues\ValueModels\MultitextCustomFieldValueModel())
                                 ->setValue($phoneNumber) // Set the phone number here
                                 ->setEnum('WORK') // Specify the phone type (WORK, HOME, etc.)
                         )
                 );
             $customFieldsValues->add($phoneFieldModel);
             $contact->setCustomFieldsValues($customFieldsValues);

             $contactsCollection = new \AmoCRM\Collections\ContactsCollection();
             $contactsCollection->add($contact);
             $this->apiClient->contacts()->add($contactsCollection);

             // Link the contact to the lead
             $newLead->setContacts($contactsCollection);

            $addedLead = $this->apiClient->leads()->addOne($newLead);
            return $addedLead;
         } catch (\AmoCRM\Exceptions\AmoCRMApiException $e) {
             throw new \Exception('Leadni yangilashda xatolik:' . $e->getMessage());
         }
    }


    public function updateLead(int $leadId, array $updatedFields = [], array $tags = [], string $message = null, array $customFields = [])
    {
        try {
            $lead = $this->apiClient->leads()->getOne($leadId);
            if (!$lead) {
                return ['is_ok' => false];
            }

            if (isset($updatedFields['name'])) {
                $lead->setName($updatedFields['name']);
            }

            if (isset($updatedFields['price'])) {
                $lead->setPrice($updatedFields['price']);
            }

            if (isset($updatedFields['pipelineId'])) {
                $lead->setPipelineId($updatedFields['pipelineId']);
            }

            if (isset($updatedFields['statusId'])) {
                $lead->setStatusId($updatedFields['statusId']);
            }

            if (!empty($tags)) {
                $tagsCollection = new TagsCollection();
                foreach ($tags as $tagName) {
                    $tag = new TagModel();
                    $tag->setName($tagName);
                    $tagsCollection->add($tag);
                }
                $lead->setTags($tagsCollection);
            }

            if (!empty($customFields)) {
                $customFieldsCollection = $lead->getCustomFieldsValues() ?: new \AmoCRM\Collections\CustomFieldsValuesCollection();

                foreach ($customFields as $fieldId => $fieldValue) {
                    $fieldModel = (new \AmoCRM\Models\CustomFieldsValues\TextCustomFieldValuesModel())
                        ->setFieldId($fieldId)
                        ->setValues(
                            (new \AmoCRM\Models\CustomFieldsValues\ValueCollections\TextCustomFieldValueCollection())
                                ->add(
                                    (new \AmoCRM\Models\CustomFieldsValues\ValueModels\TextCustomFieldValueModel())
                                        ->setValue($fieldValue)
                                )
                        );
                    $customFieldsCollection->add($fieldModel);
                }

                $lead->setCustomFieldsValues($customFieldsCollection);
            }

//            dd($lead);

            // 5. Leadni yangilash
            $updatedLead = $this->apiClient->leads()->updateOne($lead);

            // 6. Agar note qo'shmoqchi bo'lsangiz
            if ($message) {
                $notesService = $this->apiClient->notes(EntityTypesInterface::LEADS);
                $note = new CommonNote();
                $note->setEntityId($updatedLead->getId());
                $note->setText($message);
                $notesCollection = new NotesCollection();
                $notesCollection->add($note);
                $notesService->addOne($note);
            }

            return ['is_ok' => true, 'data' => $updatedLead];

        } catch (\AmoCRM\Exceptions\AmoCRMApiException $e) {
            return ['is_ok' => false];
        }
    }

}
