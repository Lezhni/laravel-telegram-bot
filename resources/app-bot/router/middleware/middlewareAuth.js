export default function middlewareAuth ({ next, store, nextMiddleware }){

    if (!auth.check()) {
        next({
            path: '/login',
        });

        return;
    } else {
        store.dispatch('setUser', auth.getUser())
    }

    return nextMiddleware()
}