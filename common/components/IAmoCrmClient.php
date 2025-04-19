<?php

namespace common\components;

interface IAmoCrmClient
{
    public function getAccountInfo();
    public function getUsers();
    public function getPipelines();
    public function getPipelineStatuses(int $pipelineId);

    public function addLeadToPipeline(string $phoneNumber ,string $leadName, string $message,array $tags , array $customFields, int $pipelineId, int $statusId, int $leadPrice);

    public function updateLead(int $leadId, array $updatedFields = [], array $tags = [], string $message = null, array $customFields = []);
}
