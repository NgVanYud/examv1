import store from '@/store';

/**
 * @param {Array} value
 * @returns {Boolean}
 * @example see @/views/permission/Directive.vue
 */
export default function checkRole(value) {
  if (value && value instanceof Array && value.length > 0) {
    const roles = store.getters && store.getters.roles;
    const requiredRoles = value;

    const hasRole = roles.some(role => {
      return requiredRoles.includes(role);
    });

    return hasRole;
  } else {
    console.error(`Need roles! Like v-role="['admin','editor']"`);
    return false;
  }
}

/**
 * Check if specified roles includes a set of roles
 *
 * @param inneed
 * @param checked (String)
 */
export function include(all, checked) {
  const counter = all.length;
  for (let i = 0; i < counter; i++) {
    if (all[i].name === checked) {
      return true;
    }
  }
  return false;
}

