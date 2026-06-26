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
      // Si le temps est écoulé et que rien n'est coché, on force l'input 0 et on valide
      quizForm.insertAdjacentHTML(
        "beforeend",
        '<input type="hidden" name="answer_id" value="0">',
      );
      quizForm.submit();
    }
  }, 1000);

  // 2. Gestion du Clic sur une réponse
  answerButtons.forEach((button) => {
    button.addEventListener("click", function (e) {
      e.preventDefault(); // On bloque la soumission immédiate pour montrer les couleurs
      clearInterval(countdown); // On arrête le chrono tout de suite

      const clickedButton = e.currentTarget;

      // Désactiver tous les boutons pour éviter le double-clic
      answerButtons.forEach((btn) => (btn.disabled = true));

      // Affichage des retours visuels (Vert / Rouge)
      answerButtons.forEach((btn) => {
        if (btn.dataset.correct === "1") {
          btn.classList.add("correct");
        } else {
          btn.classList.add("wrong");
        }
      });

      // On crée un input caché avec l'ID de la réponse cliquée
      const answerId = clickedButton.value;
      quizForm.insertAdjacentHTML(
        "beforeend",
        `<input type="hidden" name="answer_id" value="${answerId}">`,
      );

      // On attend 1,5 seconde pour laisser le joueur voir le résultat, puis on envoie le formulaire !
      setTimeout(() => {
        quizForm.submit();
      }, 1500);
    });
  });
});
