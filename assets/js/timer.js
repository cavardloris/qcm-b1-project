document.addEventListener("DOMContentLoaded", () => {
  let timeLeft = 15; // 15 secondes max
  const timerDisplay = document.getElementById("timer"); // On cible l'élément HTML du chrono qui a pour classe timer
  const quizForm = document.getElementById("quiz-form"); // On cible l'ID de notre formulaire de questions/reponses

  // Sécurité : si les éléments n'existent pas sur la page actuelle, on arrête le script
  if (!timerDisplay || !quizForm) return;

  const countdown = setInterval(() => {
    // création du compte a rebours qui soustrait 1 à la variable "timeleft" toutes les secondes
    timeLeft--;
    timerDisplay.textContent = timeLeft;

    // Fin du chrono
    if (timeLeft <= 0) {
      clearInterval(countdown); // On arrête le chrono

      // On vérifie si l'utilisateur a coché une réponse
      const hasChecked = quizForm.querySelector(
        "input[name='answer_id']:checked",
      );

      // Si aucune réponse n'est cochée, on crée un input caché avec la valeur 0
      if (!hasChecked) {
        const dummyInput = document.createElement("input");
        dummyInput.type = "hidden";
        dummyInput.name = "answer_id";
        dummyInput.value = "0";
        quizForm.appendChild(dummyInput);
      }

      // Soumission automatique du formulaire pour passer à la suite
      quizForm.submit();
    }
  }, 1000);
});
