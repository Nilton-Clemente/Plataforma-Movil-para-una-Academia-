const defaultBaseApiUrl = 'https://paleturquoise-baboon-358492.hostingersite.com/Proyecto%20php%20clase/Semana13/AcademiaUpdate6/public/api';

const config = {
    baseApiUrl: normalizeApiUrl(localStorage.getItem('baseApiUrl') || defaultBaseApiUrl),
};

const state = {
    token: localStorage.getItem('authToken'),
    user: null,
    screen: 'home',
};

const app = document.getElementById('app');
const title = document.getElementById('screen-title');
const sessionAction = document.getElementById('session-action');
const brandLogo = document.getElementById('brand-logo');

function normalizeApiUrl(url) {
    const trimmed = String(url || defaultBaseApiUrl).replace(/\/+$/, '');

    if (trimmed.endsWith('/public')) {
        return `${trimmed}/api`;
    }

    return trimmed;
}

function webBaseUrl() {
    return config.baseApiUrl.replace(/\/api$/, '');
}

function assetUrl(path) {
    return `${webBaseUrl()}/${path.replace(/^\//, '')}`;
}

document.addEventListener('deviceready', boot, false);
document.addEventListener('DOMContentLoaded', () => {
    if (!window.cordova) {
        boot();
    }
});

function boot() {
    if (brandLogo) {
        brandLogo.src = assetUrl('img/logo.png');
    }

    document.querySelectorAll('.tab').forEach((button) => {
        button.addEventListener('click', () => navigate(button.dataset.screen));
    });

    sessionAction.addEventListener('click', () => {
        if (state.token) {
            logout();
            return;
        }
        navigate('login');
    });

    renderSessionAction();
    navigate('home');
}

async function api(path, options = {}) {
    const headers = {
        Accept: 'application/json',
        'Content-Type': 'application/json',
        ...(options.headers || {}),
    };

    if (state.token) {
        headers.Authorization = `Bearer ${state.token}`;
    }

    const response = await fetch(`${config.baseApiUrl}${path}`, {
        ...options,
        headers,
    });

    const payload = await response.json().catch(() => ({
        data: null,
        message: 'Respuesta invalida del servidor.',
        errors: null,
    }));

    if (response.status === 401) {
        clearSession();
        throw new Error('Tu sesion expiro. Inicia sesion nuevamente.');
    }

    if (!response.ok) {
        throw new Error(payload.message || 'No se pudo completar la operacion.');
    }

    return payload.data;
}

function setLoading(text = 'Cargando...') {
    app.innerHTML = `<div class="loader">${escapeHtml(text)}</div>`;
}

function setError(message) {
    app.innerHTML = `
        <div class="notice stack">
            <div>
                <p class="section-kicker">Conexion</p>
                <h2>No se pudo cargar</h2>
                <p class="muted">${escapeHtml(message)}</p>
            </div>
            <button class="button" type="button" onclick="navigate(state.screen)">Reintentar</button>
        </div>
    `;
}

async function navigate(screen) {
    state.screen = screen;
    document.querySelectorAll('.tab').forEach((button) => {
        button.classList.toggle('active', button.dataset.screen === screen);
    });

    const titles = {
        home: 'Inicio',
        gallery: 'Galeria',
        blog: 'Blog',
        createBlogPost: 'Nueva publicacion',
        store: 'Tienda',
        cart: 'Carrito',
        profile: 'Perfil',
        login: 'Iniciar sesion',
        register: 'Registro',
        contact: 'Contacto',
    };

    title.textContent = titles[screen] || 'Academia Update';
    setLoading();

    try {
        if (screen === 'home') await renderHome();
        if (screen === 'gallery') await renderGallery();
        if (screen === 'blog') await renderBlog();
        if (screen === 'createBlogPost') await requireAuth(renderCreateBlogPost);
        if (screen === 'store') await requireAuth(renderStore);
        if (screen === 'cart') await requireAuth(renderCart);
        if (screen === 'profile') await requireAuth(renderProfile);
        if (screen === 'login') renderLogin();
        if (screen === 'register') renderRegister();
        if (screen === 'contact') await renderContact();
    } catch (error) {
        setError(error.message);
    }
}

async function requireAuth(callback) {
    if (!state.token) {
        renderLogin('Inicia sesion para continuar.');
        return;
    }
    await callback();
}

async function renderHome() {
    const data = await api('/mobile/home');
    const hero = data.banners?.[0] || assetUrl('img/banner1.png');

    app.innerHTML = `
        <section class="hero">
            <img src="${hero}" alt="Academia Update">
            <div class="hero-body">
                <p class="eyebrow">Bienvenido</p>
                <h2>${escapeHtml(data.title)}</h2>
                <p>${escapeHtml(data.summary)}</p>
                <div class="hero-actions">
                    <button class="button" type="button" onclick="navigate('${state.token ? 'profile' : 'register'}')">${state.token ? 'Ir a mi perfil' : 'Crear cuenta'}</button>
                    <button class="button secondary" type="button" onclick="navigate('gallery')">Ver galeria</button>
                </div>
            </div>
        </section>
        <div class="stack" style="margin-top:14px">
            ${(data.sections || []).map((section) => `
                <article class="card">
                    <p class="section-kicker">Academia</p>
                    <h2>${escapeHtml(section.title)}</h2>
                    <p class="muted">${escapeHtml(section.body)}</p>
                    <img class="section-image" src="${section.image}" alt="${escapeHtml(section.title)}">
                </article>
            `).join('')}
            <button class="button secondary" type="button" onclick="navigate('contact')">Contactos</button>
        </div>
    `;
}

async function renderGallery() {
    const data = await api('/mobile/gallery');
    app.innerHTML = `
        <section class="stack">
            <div class="card">
                <p class="section-kicker">Galeria</p>
                <h2>${escapeHtml(data.title)}</h2>
                <p class="muted">Momentos, espacios y actividades de Academia Update.</p>
            </div>
            <div class="gallery-grid">
                ${(data.images || []).map((image, index) => `<img src="${image}" alt="Galeria ${index + 1}">`).join('')}
            </div>
        </section>
    `;
}

async function renderBlog() {
    const data = await api('/blog-posts');
    const posts = data.data || [];

    app.innerHTML = `
        <section class="stack">
            <div class="card stack">
                <div>
                    <p class="section-kicker">Blog</p>
                    <h2>Publicaciones de la comunidad</h2>
                    <p class="muted">Comparte novedades, experiencias y recursos con Academia Update.</p>
                </div>
                <button class="button" type="button" onclick="navigate('${state.token ? 'createBlogPost' : 'login'}')">${state.token ? 'Crear publicacion' : 'Inicia sesion para publicar'}</button>
            </div>
            ${posts.length === 0 ? emptyState('Aun no hay publicaciones.') : posts.map((post) => `
                <article class="blog-card">
                    ${post.image_url ? `<img class="blog-image" src="${escapeAttr(post.image_url)}" alt="${escapeAttr(post.title)}">` : ''}
                    <div class="blog-body">
                        <p class="section-kicker">${escapeHtml(post.author?.name || 'Academia Update')}</p>
                        <h2>${escapeHtml(post.title)}</h2>
                        <p class="muted">${formatDate(post.published_at)}</p>
                        <p>${escapeHtml(post.content).replaceAll('\n', '<br>')}</p>
                    </div>
                </article>
            `).join('')}
        </section>
    `;
}

function renderCreateBlogPost() {
    app.innerHTML = `
        <form class="auth-card stack" onsubmit="createBlogPost(event)">
            <div>
                <p class="section-kicker">Blog</p>
                <h2>Nueva publicacion</h2>
                <p class="muted">Tu publicacion aparecera en el blog al guardarla.</p>
            </div>
            <label class="field"><span>Titulo</span><input name="title" required maxlength="255"></label>
            <label class="field"><span>Imagen URL</span><input name="image_url" type="url" placeholder="https://ejemplo.com/imagen.jpg"></label>
            <label class="field"><span>Contenido</span><textarea name="content" rows="7" required maxlength="5000"></textarea></label>
            <button class="button" type="submit">Publicar</button>
            <button class="button secondary" type="button" onclick="navigate('blog')">Volver al blog</button>
        </form>
    `;
}

async function createBlogPost(event) {
    event.preventDefault();
    const form = new FormData(event.target);
    const payload = Object.fromEntries(form.entries());

    payload.title = String(payload.title || '').trim();
    payload.content = String(payload.content || '').trim();
    payload.image_url = String(payload.image_url || '').trim();

    if (!payload.title || !payload.content) {
        alert('Completa el titulo y el contenido.');
        return;
    }

    if (payload.image_url && !isValidUrl(payload.image_url)) {
        alert('Ingresa una URL de imagen valida.');
        return;
    }

    if (!payload.image_url) {
        delete payload.image_url;
    }

    try {
        await api('/blog-posts', {
            method: 'POST',
            body: JSON.stringify(payload),
        });
        await navigate('blog');
    } catch (error) {
        alert(error.message);
    }
}

async function renderContact() {
    const data = await api('/mobile/contact');
    app.innerHTML = `
        <section class="stack">
            <iframe class="map-preview" src="${data.map_url}" loading="lazy" allowfullscreen></iframe>
            <form class="card stack" onsubmit="event.preventDefault(); alert('Mensaje preparado. Conecta este formulario a un endpoint cuando quieras recibir contactos desde la app.');">
                <div>
                    <p class="section-kicker">Contactos</p>
                    <h2>Escribenos</h2>
                    <p class="muted">Completa tus datos y comunicate con la academia.</p>
                </div>
                <label class="field"><span>Apellidos y nombres</span><input required></label>
                <label class="field"><span>Direccion</span><input></label>
                <label class="field"><span>Correo</span><input type="email" required></label>
                <label class="field"><span>Comentarios</span><textarea rows="4"></textarea></label>
                <button class="button" type="submit">Enviar</button>
            </form>
        </section>
    `;
}

async function renderStore() {
    const data = await api('/books');
    const books = data.data || [];

    app.innerHTML = `
        <section class="stack">
            <div class="card">
                <p class="section-kicker">Tienda</p>
                <h2>Libros disponibles</h2>
                <p class="muted">Materiales de estudio para acompanar tu aprendizaje.</p>
            </div>
            ${books.length === 0 ? emptyState('Aun no hay libros publicados.') : `
                <div class="grid">
                    ${books.map((book) => `
                        <article class="book-card">
                            <div class="book-head">
                                <div class="book-icon">B</div>
                                <div>
                                    <h2>${escapeHtml(book.title)}</h2>
                                    <p class="muted">Libro academico</p>
                                </div>
                            </div>
                            <p class="muted">${escapeHtml(book.description)}</p>
                            <div class="book-purchase">
                                <p class="price">S/ ${Number(book.price).toFixed(2)}</p>
                                <button class="button" type="button" onclick="addToCart(${book.id})">Agregar</button>
                            </div>
                        </article>
                    `).join('')}
                </div>
            `}
        </section>
    `;
}

async function addToCart(bookId) {
    try {
        await api(`/cart/books/${bookId}`, {
            method: 'POST',
            body: JSON.stringify({ quantity: 1 }),
        });
        await navigate('cart');
    } catch (error) {
        alert(error.message);
    }
}

async function renderCart() {
    const cart = await api('/cart');
    const items = cart.items || [];

    app.innerHTML = `
        <section class="stack">
            <div class="card">
                <p class="section-kicker">Tienda</p>
                <h2>Carrito de compras</h2>
                <p class="muted">Revisa tus libros antes de continuar con PayPal.</p>
            </div>
            ${items.length === 0 ? emptyState('Tu carrito esta vacio.') : `
                ${items.map((item) => `
                    <article class="cart-row">
                        <div class="cart-title-row">
                            <div>
                                <h2>${escapeHtml(item.title)}</h2>
                                <p class="muted">S/ ${Number(item.price).toFixed(2)} por unidad</p>
                            </div>
                            <p class="total">S/ ${Number(item.subtotal).toFixed(2)}</p>
                        </div>
                        <div class="qty">
                            <button type="button" onclick="setQuantity(${item.book_id}, ${item.quantity - 1})">-</button>
                            <span>${item.quantity}</span>
                            <button type="button" onclick="setQuantity(${item.book_id}, ${item.quantity + 1})">+</button>
                        </div>
                        <button class="button danger" type="button" onclick="removeFromCart(${item.book_id})">Quitar</button>
                    </article>
                `).join('')}
                <div class="card stack">
                    <h2>Resumen</h2>
                    <div>
                        <div class="summary-row"><span>Productos</span><strong>${cart.count}</strong></div>
                        <div class="summary-row"><span>Total</span><strong class="total">S/ ${Number(cart.total).toFixed(2)}</strong></div>
                    </div>
                    <button class="button" type="button" onclick="openCheckout()">Pagar con PayPal</button>
                    <button class="button secondary" type="button" onclick="clearCart()">Vaciar carrito</button>
                </div>
            `}
        </section>
    `;
}

async function setQuantity(bookId, quantity) {
    if (quantity < 1) {
        await removeFromCart(bookId);
        return;
    }

    const cart = await api('/cart');
    const quantities = {};
    (cart.items || []).forEach((item) => {
        quantities[item.book_id] = item.book_id === bookId ? quantity : item.quantity;
    });

    await api('/cart', {
        method: 'PUT',
        body: JSON.stringify({ quantities }),
    });
    await renderCart();
}

async function removeFromCart(bookId) {
    await api(`/cart/books/${bookId}`, { method: 'DELETE' });
    await renderCart();
}

async function clearCart() {
    await api('/cart', { method: 'DELETE' });
    await renderCart();
}

async function openCheckout() {
    try {
        const data = await api('/cart/checkout-link', { method: 'POST' });
        if (window.cordova && window.cordova.InAppBrowser) {
            window.cordova.InAppBrowser.open(data.url, '_blank', 'location=yes,clearcache=yes');
            return;
        }
        window.open(data.url, '_blank');
    } catch (error) {
        alert(error.message);
    }
}

function renderLogin(message = '') {
    app.innerHTML = `
        <form class="auth-card stack" onsubmit="login(event)">
            <div>
                <p class="section-kicker">Acceso</p>
                <h2>Iniciar sesion</h2>
                ${message ? `<p class="muted">${escapeHtml(message)}</p>` : '<p class="muted">Ingresa para acceder a tu tienda, carrito y perfil.</p>'}
            </div>
            <label class="field"><span>Correo</span><input name="email" type="email" required autocomplete="email"></label>
            <label class="field"><span>Contrasena</span><input name="password" type="password" required autocomplete="current-password"></label>
            <button class="button" type="submit">Iniciar sesion</button>
            <button class="button secondary" type="button" onclick="navigate('register')">Crear cuenta</button>
        </form>
    `;
}

async function login(event) {
    event.preventDefault();
    const form = new FormData(event.target);

    try {
        const data = await api('/login', {
            method: 'POST',
            body: JSON.stringify(Object.fromEntries(form.entries())),
        });
        saveSession(data);
        await navigate('profile');
    } catch (error) {
        alert(error.message);
    }
}

function renderRegister() {
    app.innerHTML = `
        <form class="auth-card stack" onsubmit="register(event)">
            <div>
                <p class="section-kicker">Registro</p>
                <h2>Crear cuenta</h2>
                <p class="muted">Completa tus datos para acceder a la experiencia movil.</p>
            </div>
            <label class="field"><span>Nombre</span><input name="name" required autocomplete="name"></label>
            <label class="field"><span>Correo</span><input name="email" type="email" required autocomplete="email"></label>
            <label class="field"><span>Telefono</span><input name="phone" autocomplete="tel"></label>
            <label class="field"><span>Direccion</span><input name="address" autocomplete="street-address"></label>
            <label class="field"><span>Contrasena</span><input name="password" type="password" required autocomplete="new-password"></label>
            <label class="field"><span>Confirmar contrasena</span><input name="password_confirmation" type="password" required autocomplete="new-password"></label>
            <button class="button" type="submit">Crear cuenta</button>
            <button class="button secondary" type="button" onclick="navigate('login')">Ya tengo cuenta</button>
        </form>
    `;
}

async function register(event) {
    event.preventDefault();
    const form = new FormData(event.target);

    try {
        const data = await api('/register', {
            method: 'POST',
            body: JSON.stringify(Object.fromEntries(form.entries())),
        });
        saveSession(data);
        await navigate('profile');
    } catch (error) {
        alert(error.message);
    }
}

async function renderProfile() {
    const user = await api('/me');
    state.user = user;

    app.innerHTML = `
        <form class="auth-card stack" onsubmit="saveProfile(event)">
            <div>
                <p class="section-kicker">Panel</p>
                <h2>Mi perfil</h2>
                <p class="muted">Mantiene tus datos actualizados para la academia.</p>
            </div>
            <label class="field"><span>Nombre</span><input name="name" value="${escapeAttr(user.name)}" required></label>
            <label class="field"><span>Correo</span><input name="email" type="email" value="${escapeAttr(user.email)}" required></label>
            <label class="field"><span>Telefono</span><input name="phone" value="${escapeAttr(user.phone || '')}"></label>
            <label class="field"><span>Direccion</span><input name="address" value="${escapeAttr(user.address || '')}"></label>
            <label class="field"><span>Bio</span><textarea name="bio" rows="4">${escapeHtml(user.bio || '')}</textarea></label>
            <button class="button" type="submit">Guardar perfil</button>
            <button class="button secondary" type="button" onclick="logout()">Salir</button>
        </form>
    `;
}

async function saveProfile(event) {
    event.preventDefault();
    const form = new FormData(event.target);

    try {
        await api('/me', {
            method: 'PUT',
            body: JSON.stringify(Object.fromEntries(form.entries())),
        });
        alert('Perfil actualizado correctamente.');
        await renderProfile();
    } catch (error) {
        alert(error.message);
    }
}

async function logout() {
    try {
        if (state.token) {
            await api('/logout', { method: 'POST' });
        }
    } catch (error) {
        console.warn(error);
    }
    clearSession();
    await navigate('home');
}

function saveSession(data) {
    state.token = data.token;
    state.user = data.user;
    localStorage.setItem('authToken', data.token);
    renderSessionAction();
}

function clearSession() {
    state.token = null;
    state.user = null;
    localStorage.removeItem('authToken');
    renderSessionAction();
}

function renderSessionAction() {
    sessionAction.textContent = state.token ? 'Salir' : 'Entrar';
}

function emptyState(message) {
    return `
        <div class="notice stack">
            <div>
                <p class="section-kicker">Sin contenido</p>
                <h2>${escapeHtml(message)}</h2>
                <p class="muted">Cuando haya contenido disponible aparecera aqui.</p>
            </div>
            <button class="button secondary" type="button" onclick="navigate('home')">Volver al inicio</button>
        </div>
    `;
}

function isValidUrl(value) {
    try {
        const url = new URL(value);
        return ['http:', 'https:'].includes(url.protocol);
    } catch (error) {
        return false;
    }
}

function formatDate(value) {
    if (!value) {
        return '';
    }

    return new Date(value).toLocaleDateString('es-PE', {
        day: '2-digit',
        month: 'short',
        year: 'numeric',
    });
}

function escapeHtml(value) {
    return String(value ?? '')
        .replaceAll('&', '&amp;')
        .replaceAll('<', '&lt;')
        .replaceAll('>', '&gt;')
        .replaceAll('"', '&quot;')
        .replaceAll("'", '&#039;');
}

function escapeAttr(value) {
    return escapeHtml(value);
}
