export default function middlewareNotAuth ({ to, from, next }){
    if (auth.check()) {
        next({
            path: '/menu',
        });

        return;
    }
    return next()
}