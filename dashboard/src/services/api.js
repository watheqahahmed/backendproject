// src/services/api.js
const API_URL = "http://localhost:8080/dashboard/api.php"; // تأكد من المسار الصحيح لـ PHP

// 🟢 إنشاء مشروع جديد
export async function createProject(project) {
  const res = await fetch(`${API_URL}?request=projects`, {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify(project),
  });
  return res.json();
}

// 🟢 جلب كل المشاريع
export async function getProjects() {
  const res = await fetch(`${API_URL}?request=projects`);
  return res.json();
}

// 🟢 جلب مشروع محدد بالـ ID
export async function getProjectById(id) {
  const res = await fetch(`${API_URL}?request=projects&id=${id}`);
  return res.json();
}

// 🟢 تحديث مشروع
export async function updateProject(id, project) {
  const res = await fetch(`${API_URL}?request=projects&id=${id}`, {
    method: "PUT",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify(project),
  });
  return res.json();
}

// 🟢 حذف مشروع
export async function deleteProject(id) {
  const res = await fetch(`${API_URL}?request=projects&id=${id}`, {
    method: "DELETE",
  });
  return res.json();
}
