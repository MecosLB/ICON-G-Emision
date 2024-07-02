import { validateToken } from "@/modules/helpers/auth";

export const authGuard = async (to, from, next) => {
    const storage = localStorage;

    const { error, message } = await validateToken();

    if (!error) {
        next({ name: 'dashboard' });
        return;
    }

    localStorage.removeItem('iconG');
    next();
};