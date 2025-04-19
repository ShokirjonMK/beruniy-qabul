const text = document.querySelector(".circle-text");
text.innerHTML = text.innerText.split("").map((char, i) => `<span style="transform:rotate(${i * 12.1}deg)">${char}</span>`).join("");