document.addEventListener("DOMContentLoaded", function () {
  document
    .getElementById("wcfbo-form")
    .addEventListener("submit", function (event) {
      let dob = document.querySelector("input[name='wcfbo_dob']").value;
      let tob = document.querySelector("input[name='wcfbo_tob']").value;

      let dobRegex = /^\d{2}\/\d{2}\/\d{4}$/;
      let tobRegex = /^\d{2}:\d{2}$/;

      if (!dobRegex.test(dob)) {
        alert("Invalid Date Format. Please enter DD/MM/YYYY.");
        event.preventDefault();
        return;
      }
      if (!tobRegex.test(tob)) {
        alert("Invalid Time Format. Please enter HH:MM.");
        event.preventDefault();
        return;
      }
    });
});
