const uid = document.getElementById("uid");
const csrfToken = document
  .querySelector('meta[name="csrf-token"]')
  .getAttribute("content");
let scanTimer;
let selectedMemberId = null;
let selectedMemberAction = "update";
let membersPage = 1;
let logsPage = 1;
let logsSearchTimer;
let logsRequestId = 0;
let pendingCardId = null;
let pendingLogsDeletion = null;
let pendingMemberDeletionId = null;
let selectedLogId = null;
let pendingLogDeletionId = null;
let deleteMemberTrigger = null;
const membersById = new Map();
const logsById = new Map();

function escapeHtml(value) {
  const div = document.createElement("div");
  div.textContent = value ?? "";
  return div.innerHTML;
}

function formatDate(value) {
  return value ? value.slice(0, 10) : "-";
}

function openAddMemberModal() {
  const modal = document.getElementById("add-member-modal");
  modal.classList.add("open");
  modal.setAttribute("aria-hidden", "false");
  document.getElementById("member-name").focus();
}

function closeAddMemberModal() {
  const modal = document.getElementById("add-member-modal");
  document.getElementById("open-add-member-modal").focus();
  modal.classList.remove("open");
  modal.setAttribute("aria-hidden", "true");
}

uid.addEventListener("keydown", (event) => {
  if (event.key === "Enter") {
    event.preventDefault();
  }
});

async function submitMember(event) {
  event.preventDefault();
  const form = event.target;
  const result = document.getElementById("member-result");
  const data = Object.fromEntries(new FormData(form));
  data.nfc_uid = data.nfc_uid.trim().toUpperCase();

  try {
    const response = await fetch("/api/members", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        Accept: "application/json",
        "X-CSRF-TOKEN": csrfToken,
      },
      body: JSON.stringify(data),
    });
    const payload = await response.json();
    if (!response.ok) {
      const messages = Object.values(payload.errors || {}).flat();
      throw new Error(
        messages.join(", ") ||
          payload.message ||
          "Data member tidak dapat disimpan.",
      );
    }
    result.className = "result ok";
    result.textContent = `Member ${payload.name} berhasil ditambahkan dengan ID card ${payload.nfc_uid}.`;
    form.reset();
    loadData();
  } catch (error) {
    result.className = "result bad";
    result.textContent = error.message;
  }
}

async function scanCardInfo() {
  const cardId = uid.value.trim().toUpperCase();
  if (!cardId) {
    showScanMessageModal(
      "UID Kartu Kosong",
      "Silakan isi UID kartu terlebih dahulu.",
    );
    return;
  }

  const member = [...membersById.values()].find(
    (item) => item.nfc_uid && item.nfc_uid.toUpperCase() === cardId,
  );
  if (!member) {
    showScanMessageModal("Kartu Tidak Terdaftar", "Kartu tidak terdaftar.");
    return;
  }

  pendingCardId = cardId;
  document.getElementById("scan-title").textContent = "Konfirmasi Akses";
  document.getElementById("scan-enter").style.display = "block";
  document.getElementById("scan-details").innerHTML =
    '<div>Nama: <strong id="scan-member-name"></strong></div><div>Jenis paket: <strong id="scan-membership-type"></strong></div><div>Masa berlaku: <strong id="scan-valid-until"></strong></div><div>Sisa scan paket: <strong id="scan-remaining"></strong></div>';
  const scanLimit = { "1 Bulan": 4, "2 Bulan": 9 }[member.membership_type];
  const remainingScans =
    scanLimit === undefined
      ? "-"
      : Math.max(scanLimit - Number(member.scan_count || 0), 0);
  document.getElementById("scan-member-name").textContent = member.name;
  document.getElementById("scan-membership-type").textContent =
    member.membership_type;
  document.getElementById("scan-valid-until").textContent = formatDate(
    member.valid_until,
  );
  document.getElementById("scan-remaining").textContent = remainingScans;
  const modal = document.getElementById("scan-modal");
  modal.classList.add("open");
  modal.setAttribute("aria-hidden", "false");
}

function showScanMessageModal(title, message) {
  pendingCardId = null;
  document.getElementById("scan-title").textContent = title;
  document.getElementById("scan-details").textContent = message;
  document.getElementById("scan-enter").style.display = "none";
  const modal = document.getElementById("scan-modal");
  modal.classList.add("open");
  modal.setAttribute("aria-hidden", "false");
}

function closeScanModal() {
  pendingCardId = null;
  const modal = document.getElementById("scan-modal");
  modal.classList.remove("open");
  modal.setAttribute("aria-hidden", "true");
  uid.focus();
}

function closeRefreshModal() {
  const modal = document.getElementById("refresh-modal");
  modal.classList.remove("open");
  modal.setAttribute("aria-hidden", "true");
}

function resetMemberForm() {
  document.getElementById("member-form").reset();
  const result = document.getElementById("member-result");
  result.className = "";
  result.textContent = "";
  const modal = document.getElementById("reset-member-modal");
  modal.classList.add("open");
  modal.setAttribute("aria-hidden", "false");
}

function closeResetMemberModal() {
  const modal = document.getElementById("reset-member-modal");
  modal.classList.remove("open");
  modal.setAttribute("aria-hidden", "true");
}

function openLogoutModal() {
  const modal = document.getElementById("logout-modal");
  modal.classList.add("open");
  modal.setAttribute("aria-hidden", "false");
}

function closeLogoutModal() {
  const modal = document.getElementById("logout-modal");
  modal.classList.remove("open");
  modal.setAttribute("aria-hidden", "true");
}

function openPasswordModal() {
  const modal = document.getElementById("password-modal");
  modal.classList.add("open");
  modal.setAttribute("aria-hidden", "false");
}

function closePasswordModal() {
  const modal = document.getElementById("password-modal");
  modal.classList.remove("open");
  modal.setAttribute("aria-hidden", "true");
}

function openAdminModal() {
  const modal = document.getElementById("admin-modal");
  if (!modal) return;
  modal.classList.add("open");
  modal.setAttribute("aria-hidden", "false");
}

function closeAdminModal() {
  const modal = document.getElementById("admin-modal");
  if (!modal) return;
  modal.classList.remove("open");
  modal.setAttribute("aria-hidden", "true");
}

function openAdminMenuModal() {
  const modal = document.getElementById("admin-menu-modal");
  if (!modal) return;
  modal.classList.add("open");
  modal.setAttribute("aria-hidden", "false");
}

function closeAdminMenuModal() {
  const modal = document.getElementById("admin-menu-modal");
  if (!modal) return;
  document.getElementById("open-admin-menu-modal")?.focus();
  modal.classList.remove("open");
  modal.setAttribute("aria-hidden", "true");
}

function openAdminMenuAction(openModal) {
  closeAdminMenuModal();
  openModal();
}

function openPurgeModal() {
  const modal = document.getElementById("purge-modal");
  if (!modal) return;
  document.getElementById("purge-form").reset();
  const result = document.getElementById("purge-result");
  result.className = "";
  result.textContent = "";
  modal.classList.add("open");
  modal.setAttribute("aria-hidden", "false");
  document.getElementById("purge-password").focus();
}

function closePurgeModal() {
  const modal = document.getElementById("purge-modal");
  if (!modal) return;
  document.getElementById("open-admin-menu-modal")?.focus();
  document.getElementById("purge-form").reset();
  modal.classList.remove("open");
  modal.setAttribute("aria-hidden", "true");
}

async function purgeDatabase(event) {
  event.preventDefault();
  const form = event.target;
  const submitButton = document.getElementById("purge-submit");
  const result = document.getElementById("purge-result");
  submitButton.disabled = true;
  result.className = "";
  result.textContent = "";

  try {
    const response = await fetch("/api/admin/purge", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        Accept: "application/json",
        "X-CSRF-TOKEN": csrfToken,
      },
      body: JSON.stringify(Object.fromEntries(new FormData(form))),
    });
    const payload = await response.json();
    if (!response.ok) {
      const messages = Object.values(payload.errors || {}).flat();
      throw new Error(messages.join(", ") || payload.message || "Database tidak dapat di-purge.");
    }

    form.reset();
    result.className = "result ok";
    result.textContent = `${payload.message} ${payload.deleted.members} member dan ${payload.deleted.logs} riwayat scan dihapus.`;
  } catch (error) {
    result.className = "result bad";
    result.textContent = error.message || "Database tidak dapat di-purge.";
    return;
  } finally {
    submitButton.disabled = false;
  }

  try {
    await loadData();
  } catch (error) {
    result.className = "result bad";
    result.textContent += ` Namun, data dashboard tidak dapat diperbarui: ${error.message}`;
  }
}

async function confirmCardEntry() {
  if (!pendingCardId) return;
  try {
    const response = await fetch("/api/scan", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        Accept: "application/json",
        "X-CSRF-TOKEN": csrfToken,
      },
      body: JSON.stringify({
        nfc_uid: pendingCardId,
        reader_type: "usb-keyboard",
      }),
    });
    const result = await response.json();
    if (!response.ok)
      throw new Error(result.message || "Scan kartu gagal diproses.");
    closeScanModal();
    uid.value = "";
    showScanResultModal(result);
    loadData();
  } catch (error) {
    alert(error.message);
  }
}

function showScanResultModal(result) {
  const modal = document.getElementById("scan-modal");
  const enterButton = document.getElementById("scan-enter");
  enterButton.style.display = "none";
  document.getElementById("scan-title").textContent = result.ok
    ? "Akses Diterima"
    : "Akses Ditolak";
  const details = document.getElementById("scan-details");
  details.innerHTML = `<div class="result ${result.ok ? "ok" : "bad"}">${escapeHtml(result.message)}</div>`;
  if (result.member) {
    const scanLimit = { "1 Bulan": 4, "2 Bulan": 9 }[
      result.member.membership_type
    ];
    const remainingScans =
      scanLimit === undefined
        ? "-"
        : Math.max(scanLimit - Number(result.member.scan_count || 0), 0);
    details.innerHTML += `<div>Nama: <strong>${escapeHtml(result.member.name)}</strong></div><div>Jenis paket: <strong>${escapeHtml(result.member.membership_type)}</strong></div><div>Masa berlaku: <strong>${escapeHtml(formatDate(result.member.valid_until))}</strong></div><div>Sisa scan paket: <strong>${remainingScans}</strong></div>`;
  }
  modal.classList.add("open");
  modal.setAttribute("aria-hidden", "false");
}

function openUpdateModal(memberId) {
  const member = membersById.get(memberId);
  if (!member) return;
  selectedMemberId = memberId;
  selectedMemberAction = "update";
  setUpdateDetailsVisible(true);
  document.getElementById("update-title").textContent = "Update Membership";
  document.getElementById("update-submit").textContent = "Update";
  fillUpdateForm(member);
  const membershipType = document.getElementById("update-membership-type");
  if (
    ![...membershipType.options].some(
      (option) => option.value === member.membership_type,
    )
  ) {
    membershipType.add(
      new Option(member.membership_type, member.membership_type),
    );
  }
  membershipType.value = member.membership_type;
  syncLiquidSelect(membershipType);
  document.getElementById("update-valid-until").value = formatDate(
    member.valid_until,
  );
  document.getElementById("update-status").value = member.status;
  const modal = document.getElementById("update-modal");
  modal.classList.add("open");
  modal.setAttribute("aria-hidden", "false");
}

function openExtendModal(memberId) {
  const member = membersById.get(memberId);
  if (!member) return;
  selectedMemberId = memberId;
  selectedMemberAction = "extend";
  setUpdateDetailsVisible(false);
  document.getElementById("update-title").textContent = "Perpanjang Paket";
  document.getElementById("update-submit").textContent = "Perpanjang";
  fillUpdateForm(member);
  const membershipType = document.getElementById("update-membership-type");
  membershipType.value = ["1 Bulan", "2 Bulan"].includes(member.membership_type)
    ? member.membership_type
    : "1 Bulan";
  syncLiquidSelect(membershipType);
  document.getElementById("update-valid-until").value = formatDate(
    member.valid_until,
  );
  document.getElementById("update-status").value = "active";
  syncLiquidSelect(document.getElementById("update-status"));
  const modal = document.getElementById("update-modal");
  modal.classList.add("open");
  modal.setAttribute("aria-hidden", "false");
}

function setUpdateDetailsVisible(visible) {
  const fields = document.getElementById("update-member-fields");
  fields.style.display = visible ? "grid" : "none";
  fields.querySelectorAll("input").forEach((input) => {
    input.required = visible;
  });
}

function fillUpdateForm(member) {
  document.getElementById("update-name").value = member.name || "";
  document.getElementById("update-created-date").value = member.created_date
    ? formatDate(member.created_date)
    : "";
  document.getElementById("update-nik").value = member.nik || "";
  document.getElementById("update-phone").value = member.phone || "";
  document.getElementById("update-address").value = member.address || "";
  document.getElementById("update-room-number").value =
    member.room_number || "";
  document.getElementById("update-nfc-uid").value = member.nfc_uid || "";
}

function closeUpdateModal() {
  selectedMemberId = null;
  const modal = document.getElementById("update-modal");
  modal.classList.remove("open");
  modal.setAttribute("aria-hidden", "true");
}

async function submitMemberUpdate(event) {
  event.preventDefault();
  if (selectedMemberId === null) return;
  if (selectedMemberAction === "extend") {
    await extendMember(selectedMemberId, event.target);
  } else {
    await updateMember(selectedMemberId, event.target);
  }
}

async function extendMember(memberId, form) {
  const data = Object.fromEntries(new FormData(form));
  try {
    const response = await fetch(`/api/members/${memberId}/extend`, {
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
    if (!response.ok) {
      const messages = Object.values(payload.errors || {}).flat();
      throw new Error(
        messages.join(", ") ||
          payload.message ||
          "Paket tidak dapat diperpanjang.",
      );
    }
    closeUpdateModal();
    loadData();
  } catch (error) {
    alert(error.message);
  }
}

async function updateMember(memberId, form) {
  const data = Object.fromEntries(new FormData(form));
  try {
    const response = await fetch(`/api/members/${memberId}`, {
      method: "PUT",
      headers: {
        "Content-Type": "application/json",
        Accept: "application/json",
        "X-CSRF-TOKEN": csrfToken,
      },
      body: JSON.stringify(data),
    });
    const payload = await response.json();
    if (!response.ok) {
      const messages = Object.values(payload.errors || {}).flat();
      throw new Error(messages.join(", ") || "Membership tidak dapat diubah.");
    }
    closeUpdateModal();
    loadData();
  } catch (error) {
    alert(error.message);
  }
}

function openDeleteMemberModal(memberId) {
  if (window.canDeleteMembers !== true) return;

  const member = membersById.get(memberId);
  if (!member) return;

  pendingMemberDeletionId = memberId;
  deleteMemberTrigger = document.activeElement;
  document.getElementById("confirm-delete-member-message").textContent =
    `Apakah Anda yakin ingin menghapus member ${member.name}? Data yang sudah dihapus tidak dapat dikembalikan.`;
  const modal = document.getElementById("confirm-delete-member-modal");
  modal.classList.add("open");
  modal.setAttribute("aria-hidden", "false");
  modal.querySelector("button.secondary").focus();
}

function closeDeleteMemberModal(restoreFocus = true) {
  const trigger = deleteMemberTrigger;
  document.activeElement?.blur();
  pendingMemberDeletionId = null;
  deleteMemberTrigger = null;
  const modal = document.getElementById("confirm-delete-member-modal");
  modal.classList.remove("open");
  modal.setAttribute("aria-hidden", "true");
  if (restoreFocus && trigger?.isConnected) trigger.focus();
}

async function confirmDeleteMember() {
  if (window.canDeleteMembers !== true) return;
  if (pendingMemberDeletionId === null) return;

  const confirmButton = document.getElementById("confirm-delete-member-button");
  confirmButton.disabled = true;
  try {
    const response = await fetch(`/api/members/${pendingMemberDeletionId}`, {
      method: "DELETE",
      headers: { Accept: "application/json", "X-CSRF-TOKEN": csrfToken },
    });
    if (!response.ok) {
      const payload = await response.json();
      throw new Error(payload.message || "Member tidak dapat dihapus.");
    }

    closeDeleteMemberModal(false);
    await loadData();
  } catch (error) {
    alert(error.message || "Member tidak dapat dihapus.");
  } finally {
    confirmButton.disabled = false;
  }
}

function openExportModal() {
  const modal = document.getElementById("export-modal");
  modal.classList.add("open");
  modal.setAttribute("aria-hidden", "false");
}

function closeExportModal() {
  const modal = document.getElementById("export-modal");
  modal.classList.remove("open");
  modal.setAttribute("aria-hidden", "true");
}

function openEditLogModal(logId) {
  if (window.canManageScanLogs !== true) return;
  const log = logsById.get(logId);
  if (!log) return;

  selectedLogId = logId;
  const scannedAt = new Date(log.scanned_at);
  const localScannedAt = new Date(
    scannedAt.getTime() - scannedAt.getTimezoneOffset() * 60000,
  )
    .toISOString()
    .slice(0, 16);
  document.getElementById("edit-log-scanned-at").value = localScannedAt;
  document.getElementById("edit-log-uid").value = log.nfc_uid || "";
  document.getElementById("edit-log-result").value = log.result || "";
  document.getElementById("edit-log-reason").value = log.reason || "";
  const result = document.getElementById("edit-log-result-message");
  result.className = "";
  result.textContent = "";
  const modal = document.getElementById("edit-log-modal");
  modal.classList.add("open");
  modal.setAttribute("aria-hidden", "false");
  document.getElementById("edit-log-scanned-at").focus();
}

function closeEditLogModal() {
  const modal = document.getElementById("edit-log-modal");
  modal.classList.remove("open");
  modal.setAttribute("aria-hidden", "true");
  selectedLogId = null;
}

async function submitLogUpdate(event) {
  event.preventDefault();
  if (window.canManageScanLogs !== true || selectedLogId === null) return;

  const form = event.target;
  const submitButton = form.querySelector('button[type="submit"]');
  const result = document.getElementById("edit-log-result-message");
  const data = Object.fromEntries(new FormData(form));
  data.scanned_at = new Date(data.scanned_at).toISOString();
  submitButton.disabled = true;
  try {
    const response = await fetch(`/api/logs/${selectedLogId}`, {
      method: "PUT",
      headers: {
        "Content-Type": "application/json",
        Accept: "application/json",
        "X-CSRF-TOKEN": csrfToken,
      },
      body: JSON.stringify(data),
    });
    const payload = await response.json();
    if (!response.ok) {
      const messages = Object.values(payload.errors || {}).flat();
      throw new Error(
        messages.join(", ") || payload.message || "Riwayat tidak dapat diperbarui.",
      );
    }
    closeEditLogModal();
    await loadData();
  } catch (error) {
    result.className = "result bad";
    result.textContent = error.message || "Riwayat tidak dapat diperbarui.";
  } finally {
    submitButton.disabled = false;
  }
}

function openDeleteLogModal(logId) {
  if (window.canManageScanLogs !== true) return;
  const log = logsById.get(logId);
  if (!log) return;

  pendingLogDeletionId = logId;
  document.getElementById("delete-log-message").textContent =
    `Riwayat scan UID ${log.nfc_uid} pada ${new Date(log.scanned_at).toLocaleString("id-ID")} akan dihapus. Tindakan ini tidak dapat dibatalkan.`;
  const result = document.getElementById("delete-log-result");
  result.className = "";
  result.textContent = "";
  const modal = document.getElementById("delete-log-modal");
  modal.classList.add("open");
  modal.setAttribute("aria-hidden", "false");
  document.getElementById("confirm-delete-log-button").focus();
}

function closeDeleteLogModal() {
  const modal = document.getElementById("delete-log-modal");
  modal.classList.remove("open");
  modal.setAttribute("aria-hidden", "true");
  pendingLogDeletionId = null;
}

async function confirmDeleteLog() {
  if (window.canManageScanLogs !== true || pendingLogDeletionId === null) return;

  const logId = pendingLogDeletionId;
  const confirmButton = document.getElementById("confirm-delete-log-button");
  const result = document.getElementById("delete-log-result");
  confirmButton.disabled = true;
  try {
    const response = await fetch(`/api/logs/${logId}`, {
      method: "DELETE",
      headers: {
        Accept: "application/json",
        "X-CSRF-TOKEN": csrfToken,
      },
    });
    const payload = await response.json();
    if (!response.ok) {
      throw new Error(payload.message || "Riwayat tidak dapat dihapus.");
    }
    closeDeleteLogModal();
    logsPage = 1;
    await loadData();
  } catch (error) {
    result.className = "result bad";
    result.textContent = error.message || "Riwayat tidak dapat dihapus.";
  } finally {
    confirmButton.disabled = false;
  }
}

function openDeleteLogsModal() {
  const modal = document.getElementById("delete-logs-modal");
  modal.classList.add("open");
  modal.setAttribute("aria-hidden", "false");
  document.getElementById("delete-logs-from").focus();
}

function closeDeleteLogsModal() {
  const modal = document.getElementById("delete-logs-modal");
  modal.classList.remove("open");
  modal.setAttribute("aria-hidden", "true");
  pendingLogsDeletion = null;
  document.getElementById("delete-logs-open").focus();
}

function closeConfirmDeleteLogsModal() {
  const modal = document.getElementById("confirm-delete-logs-modal");
  modal.classList.remove("open");
  modal.setAttribute("aria-hidden", "true");
  pendingLogsDeletion = null;
  document.querySelector("#delete-logs-form button[type='submit']").focus();
}

function submitDeleteLogs(event) {
  event.preventDefault();
  const form = event.target;
  pendingLogsDeletion = Object.fromEntries(new FormData(form));
  document.getElementById("confirm-delete-logs-range").textContent =
    `Riwayat scan dari ${pendingLogsDeletion.from} sampai ${pendingLogsDeletion.to} akan dihapus. Tindakan ini tidak dapat dibatalkan.`;
  const modal = document.getElementById("confirm-delete-logs-modal");
  modal.classList.add("open");
  modal.setAttribute("aria-hidden", "false");
  modal.querySelector("button.delete").focus();
}

async function confirmDeleteLogs() {
  if (!pendingLogsDeletion) return;

  const data = pendingLogsDeletion;
  const modal = document.getElementById("confirm-delete-logs-modal");
  document.querySelector("#delete-logs-form button[type='submit']").focus();
  modal.classList.remove("open");
  modal.setAttribute("aria-hidden", "true");
  const result = document.getElementById("delete-logs-result");
  try {
    const response = await fetch("/api/logs", {
      method: "DELETE",
      headers: {
        "Content-Type": "application/json",
        Accept: "application/json",
        "X-CSRF-TOKEN": csrfToken,
      },
      body: JSON.stringify(data),
    });
    const payload = await response.json();
    if (!response.ok) {
      const messages = Object.values(payload.errors || {}).flat();
      throw new Error(
        messages.join(", ") ||
          payload.message ||
          "Riwayat tidak dapat dihapus.",
      );
    }
    closeDeleteLogsModal();
    pendingLogsDeletion = null;
    logsPage = 1;
    loadData();
  } catch (error) {
    pendingLogsDeletion = null;
    result.className = "result bad";
    result.textContent = error.message;
  }
}

async function submitExport(event) {
  event.preventDefault();
  const result = document.getElementById("export-result");
  const params = new URLSearchParams(new FormData(event.target));
  try {
    const response = await fetch(`/api/logs/export?${params}`);
    const logs = await response.json();
    if (!response.ok)
      throw new Error(logs.message || "Rentang tanggal tidak valid.");
    if (!logs.length)
      throw new Error("Tidak ada riwayat scan pada rentang tanggal tersebut.");
    document.getElementById("logs").innerHTML = logs
      .map(
        (log) =>
          `<tr><td>${new Date(log.scanned_at).toLocaleString("id-ID")}</td><td>${escapeHtml(log.nfc_uid)}</td><td>${escapeHtml(log.member?.name || "-")}</td><td>${escapeHtml(log.result)}</td><td>${escapeHtml(log.reason || "")}</td></tr>`,
      )
      .join("");
    closeExportModal();
    window.onafterprint = () => loadData();
    window.print();
  } catch (error) {
    result.className = "result bad";
    result.textContent = error.message;
  }
}

function exportLogsPdf() {
  window.print();
}

function renderLogsPagination(meta) {
  document.getElementById("logs-pagination").innerHTML =
    meta.last_page > 1
      ? `<button type="button" ${meta.current_page === 1 ? "disabled" : ""} onclick="changeLogsPage(${meta.current_page - 1})">&larr;</button><span>Halaman ${meta.current_page} dari ${meta.last_page}</span><button type="button" ${meta.current_page === meta.last_page ? "disabled" : ""} onclick="changeLogsPage(${meta.current_page + 1})">&rarr;</button>`
      : "";
}

function changeLogsPage(page) {
  logsPage = page;
  loadData();
}

document.getElementById("logs-search").addEventListener("input", () => {
  logsPage = 1;
  clearTimeout(logsSearchTimer);
  logsSearchTimer = setTimeout(() => loadData(), 200);
});

function renderMembersPagination(meta) {
  document.getElementById("members-pagination").innerHTML =
    meta.last_page > 1
      ? `<button type="button" ${meta.current_page === 1 ? "disabled" : ""} onclick="changeMembersPage(${meta.current_page - 1})">&larr;</button><span>Halaman ${meta.current_page} dari ${meta.last_page}</span><button type="button" ${meta.current_page === meta.last_page ? "disabled" : ""} onclick="changeMembersPage(${meta.current_page + 1})">&rarr;</button>`
      : "";
}

function changeMembersPage(page) {
  membersPage = page;
  loadData();
}

async function loadData(showRefreshModal = false) {
  const currentLogsRequest = ++logsRequestId;
  const logsParams = new URLSearchParams({
    page: String(logsPage),
    search: document.getElementById("logs-search").value.trim(),
  });
  const [membersResponse, logsResponse] = await Promise.all([
    fetch(`/api/members?page=${membersPage}`),
    fetch(`/api/logs?${logsParams}`),
  ]);
  const [membersPayload, activeMembers] = await Promise.all([
    membersResponse.json(),
    fetch("/api/members/all").then((response) => response.json()),
  ]);
  const members = membersPayload.data || [];
  const logsPayload = await logsResponse.json();
  const logs = logsPayload.data || [];
  document.getElementById("total").textContent =
    membersPayload.total ?? members.length;
  document.getElementById("active").textContent = activeMembers.filter(
    (member) => member.valid_until >= new Date().toISOString().slice(0, 10),
  ).length;
  document.getElementById("scans").textContent = logsPayload.today_count ?? 0;
  document.getElementById("members").innerHTML = members
    .map(
      (member) =>
        `<tr><td>${escapeHtml(member.member_code)}</td><td>${escapeHtml(member.name)}</td><td>${escapeHtml(formatDate(member.created_date))}</td><td>${escapeHtml(member.phone || "-")}</td><td>${escapeHtml(member.address || "-")}</td><td>${escapeHtml(member.room_number || "-")}</td><td>${escapeHtml(member.nfc_uid)}</td><td>${escapeHtml(formatDate(member.valid_until))}</td><td>${escapeHtml(member.status)}</td><td>${member.scan_count || 0}/${member.membership_type === "1 Bulan" ? 4 : member.membership_type === "2 Bulan" ? 9 : "-"}</td><td class="actions"><button type="button" class="update" onclick="openUpdateModal(${member.id})">Update</button><button type="button" class="extend" onclick="openExtendModal(${member.id})">Perpanjang</button>${window.canDeleteMembers === true ? `<button type="button" class="delete" onclick="openDeleteMemberModal(${member.id})">Delete</button>` : ""}</td></tr>`,
    )
    .join("");
  renderMembersPagination(membersPayload);
  if (currentLogsRequest === logsRequestId) {
    logsById.clear();
    logs.forEach((log) => logsById.set(log.id, log));
    document.getElementById("logs").innerHTML = logs.length
      ? logs
          .map(
            (log) => {
              const actions =
                window.canManageScanLogs === true
                  ? `<td class="actions"><button type="button" class="update" onclick="openEditLogModal(${log.id})">Update</button><button type="button" class="delete" onclick="openDeleteLogModal(${log.id})">Delete</button></td>`
                  : "";
              return `<tr><td>${new Date(log.scanned_at).toLocaleString("id-ID")}</td><td>${escapeHtml(log.nfc_uid)}</td><td>${escapeHtml(log.member?.name || "-")}</td><td>${escapeHtml(log.result)}</td><td>${escapeHtml(log.reason || "")}</td>${actions}</tr>`;
            },
          )
          .join("")
      : `<tr><td colspan="${window.canManageScanLogs === true ? 6 : 5}">${document.getElementById("logs-search").value.trim() ? "Tidak ditemukan riwayat scan yang cocok." : "Belum ada riwayat scan."}</td></tr>`;
    renderLogsPagination(logsPayload);
  }
  activeMembers.forEach((member) => membersById.set(member.id, member));

  if (showRefreshModal) {
    const modal = document.getElementById("refresh-modal");
    modal.classList.add("open");
    modal.setAttribute("aria-hidden", "false");
  }
}

loadData();
scanTimer = setInterval(loadData, 10000);

const allMembersLink = document.getElementById("all-members-link");
const pageLoading = document.getElementById("page-loading");

allMembersLink.addEventListener("click", (event) => {
  if (pageLoading.classList.contains("is-visible")) {
    event.preventDefault();
    return;
  }

  if (
    event.defaultPrevented ||
    event.button !== 0 ||
    event.metaKey ||
    event.ctrlKey ||
    event.shiftKey ||
    event.altKey ||
    allMembersLink.target === "_blank"
  ) {
    return;
  }

  event.preventDefault();
  pageLoading.classList.add("is-visible");
  pageLoading.setAttribute("aria-hidden", "false");

  const progressBar = pageLoading.querySelector('[role="progressbar"]');
  const progress = pageLoading.querySelector(".page-loading-progress");
  const percentage = pageLoading.querySelector(".page-loading-percentage");
  const startedAt = performance.now();
  const duration = 1500;

  function updateProgress(now) {
    const value = Math.min(Math.floor(((now - startedAt) / duration) * 100), 100);
    progress.style.width = `${value}%`;
    percentage.textContent = `${value}%`;
    progressBar.setAttribute("aria-valuenow", String(value));

    if (value === 100) {
      window.location.assign(allMembersLink.href);
      return;
    }

    requestAnimationFrame(updateProgress);
  }

  requestAnimationFrame(updateProgress);
});

const dashboardModalClosers = {
  "add-member-modal": closeAddMemberModal,
  "admin-menu-modal": closeAdminMenuModal,
  "scan-modal": closeScanModal,
  "refresh-modal": closeRefreshModal,
  "reset-member-modal": closeResetMemberModal,
  "logout-modal": closeLogoutModal,
  "purge-modal": closePurgeModal,
  "password-modal": closePasswordModal,
  "admin-modal": closeAdminModal,
  "update-modal": closeUpdateModal,
  "export-modal": closeExportModal,
  "edit-log-modal": closeEditLogModal,
  "delete-log-modal": closeDeleteLogModal,
  "confirm-delete-member-modal": closeDeleteMemberModal,
  "delete-logs-modal": closeDeleteLogsModal,
  "confirm-delete-logs-modal": closeConfirmDeleteLogsModal,
};

Object.entries(dashboardModalClosers).forEach(([modalId, closeModal]) => {
  const modal = document.getElementById(modalId);
  const content = modal?.querySelector(".modal-content");
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
