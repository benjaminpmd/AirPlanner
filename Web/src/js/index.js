if (localStorage.theme === "dark" || !("theme" in localStorage)) {
  document.documentElement.classList.add("dark");
} else {
  document.documentElement.classList.remove("dark");
}


function attachEvent(selector, event, fn) {
  const matches = document.querySelectorAll(selector);
  if (matches && matches.length) {
    matches.forEach((elem) => {
      elem.addEventListener(event, () => fn(elem), false);
    });
  }
}
window.onload = function () {
  attachEvent("[data-aw-toggle-menu]", "click", function (elem) {
    elem.classList.toggle("expanded");
    document.documentElement.classList.toggle("overflow-hidden");
    document.getElementById("menu")?.classList.toggle("hidden");
  });
  attachEvent("[data-aw-toggle-color-scheme]", "click", function (elem) {
    document.documentElement.classList.toggle("dark");
    localStorage.theme = document.documentElement.classList.contains("dark")
      ? "dark"
      : "light";
  });
};
window.onpageshow = function () {
  const elem = document.querySelector("[data-aw-toggle-menu]");
  if (elem) {
    elem.classList.remove("expanded");
  }
  document.documentElement.classList.remove("overflow-hidden");
  document.getElementById("menu")?.classList.add("hidden");
};