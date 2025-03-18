const notif = document.getElementById("toast-notifier");
if (notif != null) {
  const toast = new bootstrap.Toast(notif);
  toast.show();
}
