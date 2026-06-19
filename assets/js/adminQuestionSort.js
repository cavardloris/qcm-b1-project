function initQuestionFilter() {
  const filters = document.querySelectorAll(".theme-filter");

  filters.forEach((filter) => {
    filter.addEventListener("click", (e) => {
      e.preventDefault();

      filters.forEach((f) => f.classList.remove("active"));
      filter.classList.add("active");

      filterByTheme(filter.dataset.themeId);
    });
  });
}

function filterByTheme(themeId) {
  const questions = document.querySelectorAll(".question-card");

  questions.forEach((question) => {
    const id = question.dataset.themeId;

    if (themeId === "all" || id === themeId) {
      question.style.display = "block";
    } else {
      question.style.display = "none";
    }
  });
}

document.addEventListener("DOMContentLoaded", () => {
  initQuestionFilter();
});
