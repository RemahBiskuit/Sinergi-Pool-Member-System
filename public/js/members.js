const csrfToken = document
  .querySelector('meta[name="csrf-token"]')
  .getAttribute("content");
const membersById = new Map();
let selectedMemberId = null;
let selectedDeleteMemberId = null;
let deleteTriggerElement = null;
let allMembers = [];

function escapeHtml(value) {
  const div = document.createElement("div");
  div.textContent = value ?? "";
  return div.innerHTML;
}

function formatDate(value) {
  return value ? value.slice(0, 10) : "-";
}

async function loadMembers(showRefreshModal = false) {
  try {
    const response = await fetch("/api/members/all-records");
    if (!response.ok) throw new Error("Data member tidak dapat dimuat.");

    allMembers = await response.json();
    membersById.clear();
    allMembers.forEach((member) => membersById.set(member.id, member));
    renderMembers();

    if (showRefreshModal) {
      const modal = document.getElementById("refresh-modal");
      modal.classList.add("open");
      modal.setAttribute("aria-hidden", "false");
    }
  } catch (error) {
    alert(error.message || "Data member tidak dapat dimuat.");
  }
}

function closeRefreshModal() {
  const modal = document.getElementById("refresh-modal");
  modal.classList.remove("open");
  modal.setAttribute("aria-hidden", "true");
}

function renderMembers() {
  const search = document
    .getElementById("member-search")
    .value.trim()
    .toLowerCase();
  const members = allMembers.filter((member) =>
    [
      member.member_code,
      member.name,
      member.created_date,
      member.nik,
      member.phone,
      member.address,
      member.room_number,
      member.nfc_uid,
      member.membership_type,
      member.valid_until,
      member.status,
    ]
      .join(" ")
      .toLowerCase()
      .includes(search),
  );
  document.getElementById("members").innerHTML = members
    .map(
      (member) =>
        `<tr><td>${escapeHtml(member.member_code)}</td><td>${escapeHtml(member.name)}</td><td>${escapeHtml(formatDate(member.created_date))}</td><td>${escapeHtml(member.nik || "-")}</td><td>${escapeHtml(member.phone || "-")}</td><td>${escapeHtml(member.address || "-")}</td><td>${escapeHtml(member.room_number || "-")}</td><td>${escapeHtml(member.nfc_uid || "-")}</td><td>${escapeHtml(member.membership_type)}</td><td>${escapeHtml(formatDate(member.valid_until))}</td><td class="${member.status === "active" ? "active" : "inactive"}">${escapeHtml(member.status)}</td><td class="actions"><button type="button" onclick="openEdit(${member.id})">Update</button><button type="button" class="extend" onclick="openExtend(${member.id})">Perpanjang</button><button type="button" class="delete" onclick="openDeleteConfirmation(${member.id})">Delete</button></td></tr>`,
    )
    .join("");
}

function openDeleteConfirmation(memberId) {
  const member = membersById.get(memberId);
  if (!member) return;

  selectedDeleteMemberId = memberId;
  deleteTriggerElement = document.activeElement;
  document.getElementById("delete-confirmation-message").textContent =
    `Apakah Anda yakin ingin menghapus member ${member.name}?`;
  const modal = document.getElementById("delete-confirmation-modal");
  modal.classList.add("open");
  modal.setAttribute("aria-hidden", "false");
}

function closeDeleteConfirmation(restoreFocus = true) {
  const trigger = deleteTriggerElement;
  document.activeElement?.blur();
  selectedDeleteMemberId = null;
  deleteTriggerElement = null;
  const modal = document.getElementById("delete-confirmation-modal");
  modal.classList.remove("open");
  modal.setAttribute("aria-hidden", "true");
  if (restoreFocus && trigger?.isConnected) trigger.focus();
}

async function confirmDeleteMember() {
  if (selectedDeleteMemberId === null) return;

  const confirmButton = document.getElementById("confirm-delete-member");
  confirmButton.disabled = true;
  try {
    const response = await fetch(`/api/members/${selectedDeleteMemberId}`, {
      method: "DELETE",
      headers: { Accept: "application/json", "X-CSRF-TOKEN": csrfToken },
    });
    if (!response.ok) {
      alert("Member tidak dapat dihapus.");
      return;
    }

    closeDeleteConfirmation(false);
    await loadMembers();
  } catch (error) {
    alert(error.message || "Member tidak dapat dihapus.");
  } finally {
    confirmButton.disabled = false;
  }
}

function openEdit(memberId) {
  const member = membersById.get(memberId);
  selectedMemberId = memberId;
  document.getElementById("edit-name").value = member.name || "";
  document.getElementById("edit-created-date").value = formatDate(
    member.created_date,
  );
  document.getElementById("edit-nik").value = member.nik || "";
  document.getElementById("edit-phone").value = member.phone || "";
  document.getElementById("edit-address").value = member.address || "";
  document.getElementById("edit-room-number").value = member.room_number || "";
  document.getElementById("edit-nfc-uid").value = member.nfc_uid || "";
  document.getElementById("edit-membership-type").value =
    member.membership_type;
  document.getElementById("edit-valid-until").value = formatDate(
    member.valid_until,
  );
  document.getElementById("edit-status").value = member.status;
  syncLiquidSelect(document.getElementById("edit-membership-type"));
  syncLiquidSelect(document.getElementById("edit-status"));
  const modal = document.getElementById("edit-modal");
  modal.classList.add("open");
  modal.setAttribute("aria-hidden", "false");
}

function closeEdit() {
  selectedMemberId = null;
  const modal = document.getElementById("edit-modal");
  modal.classList.remove("open");
  modal.setAttribute("aria-hidden", "true");
}

function openExtend(memberId) {
  const member = membersById.get(memberId);
  selectedMemberId = memberId;
  document.getElementById("extend-membership-type").value = [
    "1 Bulan",
    "2 Bulan",
  ].includes(member.membership_type)
    ? member.membership_type
    : "1 Bulan";
  document.getElementById("extend-valid-until").value = formatDate(
    member.valid_until,
  );
  document.getElementById("extend-status").value = "active";
  syncLiquidSelect(document.getElementById("extend-membership-type"));
  syncLiquidSelect(document.getElementById("extend-status"));
  const modal = document.getElementById("extend-modal");
  modal.classList.add("open");
  modal.setAttribute("aria-hidden", "false");
}

function closeExtend() {
  const modal = document.getElementById("extend-modal");
  modal.classList.remove("open");
  modal.setAttribute("aria-hidden", "true");
}

async function submitExtend(event) {
  event.preventDefault();
  const data = Object.fromEntries(new FormData(event.target));
  const response = await fetch(`/api/members/${selectedMemberId}/extend`, {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
      Accept: "application/json",
      "X-CSRF-TOKEN": csrfToken,
    },
    body: JSON.stringify({
      membership_type: data.membership_type,
      valid_until: data.valid_until,
    }),
  });
  const payload = await response.json();
  if (!response.ok)
    return alert(
      Object.values(payload.errors || {})
        .flat()
        .join(", ") ||
        payload.message ||
        "Paket tidak dapat diperpanjang.",
    );
  closeExtend();
  loadMembers();
}

async function submitEdit(event) {
  event.preventDefault();
  const response = await fetch(`/api/members/${selectedMemberId}`, {
    method: "PUT",
    headers: {
      "Content-Type": "application/json",
      Accept: "application/json",
      "X-CSRF-TOKEN": csrfToken,
    },
    body: JSON.stringify(Object.fromEntries(new FormData(event.target))),
  });
  const payload = await response.json();
  if (!response.ok)
    return alert(
      Object.values(payload.errors || {})
        .flat()
        .join(", ") ||
        payload.message ||
        "Member tidak dapat diubah.",
    );
  closeEdit();
  loadMembers();
}

loadMembers();
document
  .getElementById("member-search")
  .addEventListener("input", renderMembers);

[
  ["edit-modal", closeEdit],
  ["extend-modal", closeExtend],
  ["delete-confirmation-modal", closeDeleteConfirmation],
  ["refresh-modal", closeRefreshModal],
].forEach(([modalId, closeModal]) => {
  const content = document
    .getElementById(modalId)
    ?.querySelector(".modal-content");
  if (!content) return;

  const closeButton = document.createElement("button");
  closeButton.type = "button";
  closeButton.className = "modal-close";
  closeButton.setAttribute("aria-label", "Tutup modal");
  closeButton.textContent = "×";
  closeButton.addEventListener("click", () => {
    closeButton.blur();
    closeModal();
  });
  content.prepend(closeButton);
});
