function sendMessage() {
  const input = document.getElementById("userInput");
  const chatlog = document.getElementById("chatlog");
  const userMessage = input.value.trim();

  if (!userMessage) return;

  chatlog.innerHTML += `<div><strong>Tú:</strong> ${userMessage}</div>`;
  
  // Mostrar mensaje "escribiendo"
  const typingDiv = document.createElement("div");
  typingDiv.id = "typing";
  typingDiv.innerHTML = `<em>Astroneer está escribiendo...</em>`;
  chatlog.appendChild(typingDiv);
  chatlog.scrollTop = chatlog.scrollHeight;

  fetch("chat.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ message: userMessage })
  })
    .then(response => response.json())
    .then(data => {
      typingDiv.remove(); // Quitar el mensaje "escribiendo"
      chatlog.innerHTML += `<div><strong>👾Astroneer:</strong> ${data.reply}</div>`;
      chatlog.scrollTop = chatlog.scrollHeight;
    })
    .catch(err => {
      typingDiv.remove(); // Quitar mensaje incluso si hay error
      chatlog.innerHTML += `<div><strong>Error:</strong> No se pudo conectar.</div>`;
    });

  input.value = "";
}

function handleKeyPress(event) {
  if (event.key === "Enter") {
    sendMessage();
  }
}

document.addEventListener("DOMContentLoaded", () => {
  const input = document.getElementById("userInput");
  input.addEventListener("keypress", handleKeyPress);
});
