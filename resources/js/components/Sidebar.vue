<template>
  <aside class="sidebar">
    <div class="sidebar-logo">Admin</div>

    <nav class="sidebar-nav">
      <div class="nav-item">
        <i class="fas fa-chart-pie"></i> الإحصائيات
      </div>

      <div class="nav-item">
        <router-link to="/dashboard">إدارة لوحه التحكم</router-link>
        <i class="fas fa-utensils"></i>
      </div>

      <div class="nav-item active">
        <router-link to="/categories"> إدارة الأقسام</router-link>
        <i class="fas fa-list"></i>
      </div>
    </nav>
    <router-view />
  </aside>
</template>

<script>
export default {
  name: 'Sidebar'
}
</script>

<style scoped>
.sidebar {
  width: 250px;
  background: #2c3e50;
  color: white;
  display: flex;
  flex-direction: column;
  height: 100vh;
}

.sidebar-logo {
  padding: 30px;
  text-align: center;
  font-size: 1.5rem;
  font-weight: bold;
  color: #e67e22;
}

.nav-item {
  padding: 15px 25px;
  cursor: pointer;
  transition: 0.3s;
  display: flex;
  align-items: center;
}

.nav-item i {
  margin-left: 10px;
  width: 20px;
}

.nav-item:hover {
  background: rgba(255, 255, 255, 0.1);
}

.nav-item.active {
  background: #e67e22;
}
</style>
