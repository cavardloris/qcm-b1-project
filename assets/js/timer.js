document.addEventListener("DOMContentLoaded", () => {
  let timeLeft = 15;
  const timerDisplay = document.getElementById("timer");
  const quizForm = document.getElementById("quiz-form");

  if (!timerDisplay || !quizForm) return;

  // 1. Lancement du chrono
  const countdown = setInterval(() => {
    timeLeft--;
    timerDisplay.textContent = timeLeft;

    if (timeLeft <= 0) {
      clearInterval(countdown); // Si le temps restant est inferieur ou egal a 0 on arrête le chrono

      // Si aucune réponse n'est cochée, on force la valeur 0
      if (!quizForm.querySelector("input[name='answer_id']:checked")) {
        quizForm.insertAdjacentHTML(
          "beforeend",
          '<input type="hidden" name="answer_id" value="0">',
        );
      }

      quizForm.submit();
    }
  }, 1000);

  quizForm.addEventListener("submit", () => {
    clearInterval(countdown); // Évite que le chrono continue de tourner pendant le changement de page
  });
});
