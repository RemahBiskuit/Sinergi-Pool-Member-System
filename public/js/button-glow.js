document.addEventListener("pointermove", (event) => {
  if (event.pointerType === "touch") return;

  const button = event.target.closest("button, .button-link, .back-link");
  if (!button) return;

  const bounds = button.getBoundingClientRect();
  button.style.setProperty("--glow-x", `${event.clientX - bounds.left}px`);
  button.style.setProperty("--glow-y", `${event.clientY - bounds.top}px`);
});
