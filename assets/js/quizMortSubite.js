document.addEventListener("DOMContentLoaded", () => {
  let timeLeft = 15;
  const timerDisplay = document.getElementById("timer");
  const quizForm = document.getElementById("quiz-form");
  const answerButtons = document.querySelectorAll(".box-answer");

  if (!quizForm) return;

  // On gère le compte à rebours
  const countdown = setInterval(() => {
    if (!timerDisplay) return;
    timeLeft--;
    timerDisplay.textContent = timeLeft;

    if (timeLeft <= 0) {
      clearInterval(countdown);
      // Si le temps est écoulé on met le chrono à 0;
      quizForm.insertAdjacentHTML(
        "beforeend",
        '<input type="hidden" name="answer_id" value="0">',
      );
      quizForm.submit();
    }
  }, 1000);

  answerButtons.forEach((button) => {
    button.addEventListener("click", function (e) {
      e.preventDefault(); // On bloque la soumission du form pour montrer les couleurs
      clearInterval(countdown); // On arrête le chrono

      const clickedButton = e.currentTarget;

      // Désactiver tous les boutons pour éviter le double-clic
      answerButtons.forEach((btn) => (btn.disabled = true));

      // On affiche les couleurs correspondantes(rouge = mauvaises réponses, vert = bonne réponse)
      answerButtons.forEach((btn) => {
        if (btn.dataset.correct === "1") {
          btn.classList.add("correct");
        } else {
          btn.classList.add("wrong");
        }
      });

      const answerId = clickedButton.value;
      quizForm.insertAdjacentHTML(
        "beforeend",
        `<input type="hidden" name="answer_id" value="${answerId}">`,
      );

      //  On laisse un délais de 1 seconde et demie au joueur pour voir le résultat
      setTimeout(() => {
        quizForm.submit();
      }, 1500);
    });
  });
});
