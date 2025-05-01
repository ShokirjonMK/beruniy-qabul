const text2 = document.querySelector(".circle-text2");
text2.innerHTML = text2.innerText.split("").map((char, i) => `<span style="transform:rotate(${i * 7.6}deg)">${char}</span>`).join("");