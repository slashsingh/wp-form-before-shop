jQuery(document).ready(function ($) {
  let fields = JSON.parse(wcfbo.form_fields || "[]");
  debugger;
  function renderFields() {
    $("#wcfbo-fields").empty();
    $("#wcfbo-preview").empty();

    fields.forEach((field, index) => {
      $("#wcfbo-fields").append(`
        <li class="field-item" data-index="${index}">
          ${field.label} (${field.type})
          <button class="remove-field" data-index="${index}">X</button>
        </li>
      `);

      let inputField = "";
      switch (field.type) {
        case "text":
          inputField = `<label>${
            field.label
          }</label><input type="text" placeholder="${field.placeholder}" ${
            field.required ? "required" : ""
          } />`;
          break;
        case "number":
          inputField = `<label>${
            field.label
          }</label><input type="number" placeholder="${field.placeholder}" ${
            field.required ? "required" : ""
          } />`;
          break;
        case "datepicker":
          inputField = `<label>${field.label}</label><input type="date" ${
            field.required ? "required" : ""
          } />`;
          break;
        case "dropdown":
          inputField = `<label>${field.label}</label><select ${
            field.required ? "required" : ""
          }>${(field.options || [])
            .map((option) => `<option>${option}</option>`)
            .join("")}</select>`;
          break;
        case "radio":
          inputField =
            `<label>${field.label}</label>` +
            (field.options || [])
              .map(
                (option) =>
                  `<input type="radio" name="radio_${index}" ${
                    field.required ? "required" : ""
                  } /> ${option}`
              )
              .join("<br>");
          break;
      }
      $("#wcfbo-preview").append(`<div>${inputField}</div>`);
    });
  }

  $("#wcfbo-fields").sortable();
  $("#wcfbo-field-types li").draggable({
    helper: "clone",
  });

  $("#wcfbo-fields").droppable({
    accept: "#wcfbo-field-types li",
    drop: function (event, ui) {
      let type = ui.helper.data("type");
      let label = prompt(
        "Enter field label:",
        type.charAt(0).toUpperCase() + type.slice(1)
      );
      let placeholder = "";
      let required = confirm("Is this field required?");
      let options = [];

      if (type === "dropdown" || type === "radio") {
        let optionsStr = prompt(
          "Enter options separated by commas:",
          "Option 1,Option 2,Option 3"
        );
        options = optionsStr.split(",").map((option) => option.trim());
      } else if (type === "text" || type === "number") {
        placeholder = prompt("Enter placeholder text:", "");
      }

      if (label) {
        fields.push({
          type: type || "text",
          label: label || "",
          placeholder: placeholder || "",
          required: required || false,
          options: options || [],
        });
        renderFields();
      }
    },
  });

  $(document).on("click", ".remove-field", function () {
    let index = $(this).data("index");
    fields.splice(index, 1);
    renderFields();
  });

  $("#wcfbo-save-form").on("click", function () {
    $.post(
      wcfbo.ajaxurl,
      {
        action: "wcfbo_save_form",
        fields: JSON.stringify(fields),
        security: wcfbo.nonce,
      },
      function (response) {
        alert(response.data);
      }
    );
  });

  renderFields();
});
