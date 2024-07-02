import { validateToken } from "@/modules/helpers/auth";

export const dashboardGuard = async (to, from, next) => {
    const storage = localStorage;

    if (storage.length == 0) {
        storage.removeItem('iconG');
        next({ name: 'login' });
        return;
    }

    if (!storage.getItem('iconG')) {
        storage.removeItem('iconG');
        next({ name: 'login' });
        return;
    }

    const { error, message } = await validateToken();

    if (error) {
        storage.removeItem('iconG');
        next({ name: 'login' });
        return;
    }

    next();
};