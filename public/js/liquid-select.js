let openLiquidSelect = null;

function syncLiquidSelect(select) {
  const wrapper = select.closest(".liquid-select");
  if (!wrapper) return;

  const trigger = wrapper.querySelector(".liquid-select-trigger");
  const menu = wrapper._liquidSelectMenu;
  const selectedOption = select.selectedOptions[0];
  if (menu.children.length !== select.options.length) {
    populateLiquidOptions(select, menu, trigger);
  }
  trigger.querySelector(".liquid-select-value").textContent =
    selectedOption?.textContent.trim() || "";

  menu.querySelectorAll(".liquid-select-option").forEach((option, index) => {
    const selected = select.options[index] === selectedOption;
    option.setAttribute("aria-selected", String(selected));
  });
}

function populateLiquidOptions(select, menu, trigger) {
  menu.replaceChildren();
  [...select.options].forEach((nativeOption, index) => {
    const option = document.createElement("div");
    option.className = "liquid-select-option";
    option.id = `${menu.id}-option-${index}`;
    option.setAttribute("role", "option");
    option.setAttribute("aria-selected", String(nativeOption.selected));
    option.setAttribute("aria-disabled", String(nativeOption.disabled));
    option.textContent = nativeOption.textContent.trim();
    option.addEventListener("click", () => {
      if (nativeOption.disabled) return;
      select.selectedIndex = index;
      select.dispatchEvent(new Event("input", { bubbles: true }));
      select.dispatchEvent(new Event("change", { bubbles: true }));
      syncLiquidSelect(select);
      closeLiquidSelect(true);
    });
    menu.append(option);
  });
}

function closeLiquidSelect(restoreFocus = false) {
  if (!openLiquidSelect) return;

  const { wrapper, trigger, menu } = openLiquidSelect;
  wrapper.classList.remove("is-open");
  trigger.setAttribute("aria-expanded", "false");
  trigger.removeAttribute("aria-activedescendant");
  menu.classList.remove("is-open");
  window.setTimeout(() => {
    if (!menu.classList.contains("is-open")) menu.hidden = true;
  }, 220);
  openLiquidSelect = null;
  if (restoreFocus) trigger.focus();
}

function openSelectMenu(wrapper) {
  const trigger = wrapper.querySelector(".liquid-select-trigger");
  const menu = wrapper._liquidSelectMenu;
  const select = wrapper.querySelector("select");
  const options = [...menu.querySelectorAll(".liquid-select-option")];
  const selectedIndex = [...select.options].findIndex((option) => option.selected);

  if (openLiquidSelect) closeLiquidSelect();
  openLiquidSelect = { wrapper, trigger, menu, activeIndex: selectedIndex };
  wrapper.classList.add("is-open");
  trigger.setAttribute("aria-expanded", "true");
  menu.hidden = false;
  menu.querySelectorAll(".liquid-select-option").forEach((option, index) => {
    option.classList.toggle("is-active", index === selectedIndex);
  });

  const bounds = trigger.getBoundingClientRect();
  const menuHeight = Math.min(menu.scrollHeight, window.innerHeight * 0.4, 280);
  const spaceBelow = window.innerHeight - bounds.bottom;
  const openAbove = spaceBelow < menuHeight + 8 && bounds.top > spaceBelow;
  menu.style.left = `${Math.max(8, Math.min(bounds.left, window.innerWidth - bounds.width - 8))}px`;
  menu.style.width = `${Math.min(bounds.width, window.innerWidth - 16)}px`;
  menu.style.top = openAbove
    ? `${Math.max(8, bounds.top - menuHeight - 6)}px`
    : `${Math.min(bounds.bottom + 6, window.innerHeight - menuHeight - 8)}px`;
  menu.style.transformOrigin = openAbove ? "bottom center" : "top center";
  requestAnimationFrame(() => menu.classList.add("is-open"));
}

function enhanceLiquidSelect(select) {
  if (select.closest(".liquid-select")) return;

  const wrapper = document.createElement("div");
  wrapper.className = "liquid-select";
  select.parentNode.insertBefore(wrapper, select);
  wrapper.append(select);
  select.classList.add("liquid-select-native");
  select.tabIndex = -1;
  select.setAttribute("aria-hidden", "true");

  const label = [...document.querySelectorAll("label[for]")].find(
    (item) => item.htmlFor === select.id,
  );
  const trigger = document.createElement("button");
  trigger.type = "button";
  trigger.id = `${select.id || "select"}-trigger`;
  trigger.className = "liquid-select-trigger";
  trigger.setAttribute("role", "combobox");
  trigger.setAttribute("aria-haspopup", "listbox");
  trigger.setAttribute("aria-expanded", "false");
  trigger.setAttribute("aria-label", label?.textContent.trim() || "Pilih opsi");
  if (label) label.htmlFor = trigger.id;

  const value = document.createElement("span");
  value.className = "liquid-select-value";
  const chevron = document.createElement("span");
  chevron.className = "liquid-select-chevron";
  chevron.setAttribute("aria-hidden", "true");
  trigger.append(value, chevron);
  wrapper.append(trigger);
  if (label) select.setAttribute("aria-label", label.textContent.trim());

  const menu = document.createElement("div");
  menu.className = "liquid-select-menu";
  menu.id = `${select.id || "select"}-options`;
  wrapper._liquidSelectMenu = menu;
  menu.setAttribute("role", "listbox");
  menu.hidden = true;
  trigger.setAttribute("aria-controls", menu.id);

  populateLiquidOptions(select, menu, trigger);

  document.body.append(menu);
  syncLiquidSelect(select);
  trigger.addEventListener("click", () => {
    if (openLiquidSelect?.wrapper === wrapper) {
      closeLiquidSelect();
    } else {
      openSelectMenu(wrapper);
    }
  });
  trigger.addEventListener("keydown", (event) => {
    const options = [...menu.querySelectorAll(".liquid-select-option")];
    if (!options.length) return;

    if (event.key === "ArrowDown" || event.key === "ArrowUp") {
      event.preventDefault();
      if (openLiquidSelect?.wrapper !== wrapper) {
        openSelectMenu(wrapper);
        return;
      }
      const direction = event.key === "ArrowDown" ? 1 : -1;
      let index = openLiquidSelect.activeIndex;
      do {
        index = (index + direction + options.length) % options.length;
      } while (select.options[index].disabled);
      openLiquidSelect.activeIndex = index;
      options.forEach((option, optionIndex) =>
        option.classList.toggle("is-active", optionIndex === index),
      );
      trigger.setAttribute("aria-activedescendant", options[index].id);
    } else if (event.key === "Escape" && openLiquidSelect?.wrapper === wrapper) {
      event.preventDefault();
      closeLiquidSelect(true);
    } else if (
      (event.key === "Enter" || event.key === " ") &&
      openLiquidSelect?.wrapper === wrapper
    ) {
      event.preventDefault();
      options[openLiquidSelect.activeIndex]?.click();
    }
  });
  select.addEventListener("change", () => syncLiquidSelect(select));
}

document.querySelectorAll("select").forEach(enhanceLiquidSelect);

document.addEventListener("pointerdown", (event) => {
  if (
    openLiquidSelect &&
    !openLiquidSelect.wrapper.contains(event.target) &&
    !openLiquidSelect.menu.contains(event.target)
  ) {
    closeLiquidSelect();
  }
});

window.addEventListener(
  "scroll",
  () => closeLiquidSelect(),
  true,
);
window.addEventListener("resize", () => closeLiquidSelect());
