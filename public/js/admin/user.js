let currentUsers = [];

fetch(`${API_URL}/user`)
  .then((r) => r.json())
  .then((res) => {
    if (res.status === "success") {
      currentUsers = res.data;
    }
  })
  .catch((err) => console.error("Error loading users:", err));

function openFormModal(id = null) {
  const modal = document.getElementById("formModal");
  const form = document.getElementById("userForm");
  const modalTitle = document.getElementById("modalTitle");
  const passHint = document.getElementById("passHint");
  const passReqIcon = document.getElementById("passReqIcon");
  const passInput = document.getElementById("password");

  form.reset();
  document.getElementById("userId").value = "";
  modal.classList.remove("hidden");
  document.body.style.overflow = "hidden";

  if (id) {
    const user = currentUsers.find((u) => u.id == id);
    if (user) {
      modalTitle.innerHTML =
        '<i class="fas fa-user-edit text-amber-500"></i> <span>Edit User Admin</span>';
      document.getElementById("userId").value = user.id;
      document.getElementById("username").value = user.username;
      document.getElementById("role").value = user.role;

      passHint.classList.remove("hidden");
      passReqIcon.classList.add("hidden");
      passInput.required = false;
    }
  } else {
    modalTitle.innerHTML =
      '<i class="fas fa-user-plus text-blue-600"></i> <span>Tambah User Admin</span>';
    passHint.classList.add("hidden");
    passReqIcon.classList.remove("hidden");
    passInput.required = true;
  }
}

function closeModal() {
  document.getElementById("formModal").classList.add("hidden");
  document.body.style.overflow = "auto";
}

document.getElementById("userForm").addEventListener("submit", function (e) {
  e.preventDefault();
  const id = document.getElementById("userId").value;
  const url = id ? `${API_URL}/user/${id}` : `${API_URL}/user`;
  const method = id ? "PUT" : "POST";

  const formData = new FormData(this);
  const dataObj = Object.fromEntries(formData.entries());

  showLoading("Menyimpan data user...");

  fetch(url, {
    method: method,
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify(dataObj),
  })
    .then((r) => r.json())
    .then((res) => {
      hideLoading();
      if (res.status === "success") {
        showSuccess(res.message);
        closeModal();
        setTimeout(() => location.reload(), 1000);
      } else {
        showError(res.message);
      }
    })
    .catch((err) => {
      hideLoading();
      showError("Kesalahan sistem saat menyimpan user");
    });
});

function deleteUser(id) {
  confirmDelete(() => {
    showLoading("Menghapus user...");
    fetch(`${API_URL}/user/${id}`, { method: "DELETE" })
      .then((r) => r.json())
      .then((res) => {
        hideLoading();
        if (res.status === "success") {
          showSuccess(res.message);
          setTimeout(() => location.reload(), 1000);
        } else {
          showError(res.message);
        }
      });
  }, "Apakah Anda yakin ingin menghapus user ini? Akun ini tidak akan bisa login lagi.");
}
