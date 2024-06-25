export const dashboardGuard = async (to, from, next) => {
    const storage = localStorage;
    
    /* if (storage.length == 0) {
        next({ name: 'login' });
        return;
    }

    if (!storage.getItem('token')) {
        next({ name: 'login' });
        return;
    } */

    next();
};