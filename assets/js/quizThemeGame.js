// document.addEventListener("DOMContentLoaded", () => {
//   document.querySelectorAll(".box-answer").forEach((button) => {
//     // dans la div box-answer, pour chaque bouton on fait la fonction suivante
//     button.addEventListener("click", function (e) {
//       // si le bouton est cliqué alors
//       e.preventDefault(); // on empeche le rechargement direct de la page

//       const form = document.querySelector(".quiz-form"); // on récupère le formulaire
//       const formData = new FormData(form); // on crée un formData qui stockera les réponses
//       formData.append("answer_id", this.value); // on lui ajoute toutes les réponses

//       document.querySelectorAll(".box-answer").forEach((btn) => {
//         if (btn.dataset.correct === "1") {
//           btn.classList.add("correct");
//         } else {
//           btn.classList.add("wrong");
//         }
//       });

//       fetch(form.action, {
//         method: form.method,
//         body: formData,
//       }).then(() => {
//         setTimeout(() => {
//           window.location.reload();
//         }, 2500); // dès que l'utilisateur choisit sa réponse on l'affiche pendant 2,5 secondes et on recharge la page
//       });
//     });
//   });
// });

document.addEventListener("DOMContentLoaded", () => {
  document.querySelectorAll(".box-answer").forEach((button) => {
    // dans la div box-answer, pour chaque bouton on fait la fonction suivante
    button.addEventListener("click", function (e) {
      // si le bouton est cliqué alors
      e.preventDefault(); // on empeche le rechargement direct de la page

      //Récupération dynamique du formulaire lié au bouton cliqué
      const form = document.querySelector(".quiz-form");
      const score = document.querySelector(".score");
      // document.querySelectorAll(".box-answer").forEach((btn) => {
      //   btn.disabled = true;
      // });
      console.log(score.textContent);
      const formData = new FormData(form); // on crée un formData qui stockera les réponses

      formData.append("answer_id", e.currentTarget.value); // on lui ajoute toutes les réponses

      document.querySelectorAll(".box-answer").forEach((btn) => {
        if (btn.dataset.correct === "1") {
          btn.classList.add("correct");
          console.log("bonne réponse");
        } else {
          btn.classList.add("wrong");
          console.log("Mauvaise réponse !!");
        }
      });

      fetch(form.action, {
        method: form.method,
        body: formData,
      })
        .then(() => {
          setTimeout(() => {
            window.location.reload();
          }, 1500); // dès que l'utilisateur choisit sa réponse on l'affiche pendant 1,5 secondes et on recharge la page
        })
        //Gestion des erreur
        .catch((error) => {
          console.error("Erreur lors de l'envoi :", error);
          window.location.reload();
        });
    });
  });
});
