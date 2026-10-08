document.addEventListener("DOMContentLoaded", () => {
  // Practical 6/11: events, DOM manipulation and array functions
  const search = document.querySelector("#productSearch");
  const cards = [...document.querySelectorAll(".product-card")];

  if (search && cards.length) {
    search.addEventListener("input", () => {
      const term = search.value.toLowerCase().trim();
      cards.forEach(card => {
        card.style.display = card.innerText.toLowerCase().includes(term) ? "" : "none";
      });
    });
  }

  // Simple client-side validation
  document.querySelectorAll("form[data-validate]").forEach(form => {
    form.addEventListener("submit", event => {
      let ok = true;
      form.querySelectorAll("[required]").forEach(input => {
        if (!input.value.trim()) {
          input.style.borderColor = "red";
          ok = false;
        } else {
          input.style.borderColor = "";
        }
      });
      if (!ok) {
        event.preventDefault();
        alert("Please fill all required fields.");
      }
    });
  });

  // Image preview for product upload
  const imageInput = document.querySelector("#image");
  const preview = document.querySelector("#imagePreview");
  if (imageInput && preview) {
    imageInput.addEventListener("change", () => {
      const file = imageInput.files[0];
      if (file) {
        preview.src = URL.createObjectURL(file);
        preview.style.display = "block";
      }
    });
  }
});
