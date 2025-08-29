<template>
  <div
    class="flex items-center justify-center min-h-screen bg-gray-100 dark:bg-gray-900"
  >
    <div
      class="bg-white dark:bg-gray-800 p-8 rounded-lg shadow-lg w-full max-w-md"
    >
      <h2 class="text-2xl font-bold mb-6 text-gray-700 dark:text-gray-200">
        إنشاء حساب جديد
      </h2>

      <!-- الاسم -->
      <div class="mb-4">
        <input
          v-model="name"
          placeholder="الاسم"
          @input="validateName"
          class="w-full p-2 border rounded bg-gray-100 dark:bg-gray-700"
        />
        <p v-if="nameError" class="text-red-500 text-sm mt-1">
          {{ nameError }}
        </p>
      </div>

      <!-- البريد الإلكتروني -->
      <div class="mb-4">
        <input
          v-model="email"
          type="email"
          placeholder="البريد الإلكتروني"
          @input="validateEmail"
          class="w-full p-2 border rounded bg-gray-100 dark:bg-gray-700"
        />
        <p v-if="emailError" class="text-red-500 text-sm mt-1">
          {{ emailError }}
        </p>
      </div>

      <!-- كلمة المرور -->
      <div class="mb-4">
        <input
          v-model="password"
          type="password"
          placeholder="كلمة المرور"
          @input="validatePassword"
          class="w-full p-2 border rounded bg-gray-100 dark:bg-gray-700"
        />
        <p v-if="passwordError" class="text-red-500 text-sm mt-1">
          {{ passwordError }}
        </p>
        <p class="text-gray-500 text-xs mt-1">
          كلمة المرور يجب أن تكون 5 أحرف على الأقل
        </p>
      </div>

      <button
        @click="signupUser"
        :disabled="
          nameError ||
          emailError ||
          passwordError ||
          !name ||
          !email ||
          !password
        "
        class="w-full bg-green-500 text-white py-2 rounded hover:bg-green-600 transition mb-4 disabled:opacity-50 disabled:cursor-not-allowed"
      >
        إنشاء الحساب
      </button>

      <p class="text-sm text-gray-500 dark:text-gray-400">
        لديك حساب؟
        <router-link to="/login" class="text-indigo-500 hover:underline"
          >تسجيل الدخول</router-link
        >
      </p>

      <div
        v-if="toast.show"
        :class="`mt-4 px-4 py-2 rounded ${
          toast.type === 'success' ? 'bg-green-500' : 'bg-red-500'
        } text-white`"
      >
        {{ toast.message }}
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from "vue";
import { useRouter } from "vue-router";

const name = ref("");
const email = ref("");
const password = ref("");
const toast = ref({ show: false, message: "", type: "success" });
const router = useRouter();

// رسائل الأخطاء لكل حقل
const nameError = ref("");
const emailError = ref("");
const passwordError = ref("");

const API_URL =
  "http://localhost:8080/backendproject/backend/api/index.php?request=register";

// دالة الإشعارات
function showToast(message, type = "success", duration = 3000) {
  toast.value = { show: true, message, type };
  setTimeout(() => (toast.value.show = false), duration);
}

// التحقق من الاسم
function validateName() {
  nameError.value = name.value.length < 2 ? "الاسم قصير جداً" : "";
}

// التحقق من البريد
function validateEmail() {
  emailError.value = email.value.includes("@")
    ? ""
    : "البريد الإلكتروني غير صالح";
}

// التحقق من كلمة المرور
function validatePassword() {
  passwordError.value =
    password.value.length < 5 ? "كلمة المرور قصيرة جداً" : "";
}

// تسجيل المستخدم
async function signupUser() {
  if (
    nameError.value ||
    emailError.value ||
    passwordError.value ||
    !name.value ||
    !email.value ||
    !password.value
  ) {
    showToast("الحقول غير صالحة", "error");
    return;
  }

  try {
    const res = await fetch(API_URL, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({
        name: name.value,
        email: email.value,
        password: password.value,
      }),
    });

    const text = await res.text();
    let data;
    try {
      data = JSON.parse(text);
    } catch {
      throw new Error("رد الخادم ليس JSON:\n" + text);
    }

    showToast(data.message, data.success ? "success" : "error");

    if (data.success) {
      localStorage.setItem("user", JSON.stringify(data.user ?? {}));
      localStorage.setItem("user_id", data.user?.id ?? null);
      router.push("/home");
    }
  } catch (err) {
    showToast(err.message, "error");
    console.error(err);
  }
}
</script>

<style scoped>
input:focus {
  outline: none;
  border-color: #22c55e;
}
button:focus {
  outline: none;
}
</style>
