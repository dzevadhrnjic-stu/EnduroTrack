const state = {
  user: { name: 'Dzevad Hrnjic', role: 'athlete' },
  activePage: 'dashboard'
};

const pages = {
  dashboard: {
    title: 'Dashboard',
    subtitle: 'Your training overview',
    render: renderDashboard
  },
  sessions: { title: 'Training sessions', subtitle: 'Record and review your activities', render: renderSessions },
  routes: { title: 'Routes', subtitle: 'Your favourite running and cycling routes', render: renderRoutes },
  plans: { title: 'Training plans', subtitle: 'Stay consistent with your plan', render: renderPlans },
  competitions: { title: 'Competitions', subtitle: 'Discover your next challenge', render: renderCompetitions },
  profile: { title: 'Profile', subtitle: 'Manage your athlete profile', render: renderProfile },
  coach: { title: 'Coach workspace', subtitle: 'Support your athletes', render: renderCoach },
  admin: { title: 'Administration', subtitle: 'Manage the EnduroTrack platform', render: renderAdmin }
};

const demoSessions = [
  { date: '21 Oct 2026', activity: 'Morning run on Vilsonovo šetalište', discipline: 'running', distance: '8.4 km', duration: '44 min', pace: '5:14 /km' },
  { date: '19 Oct 2026', activity: 'Gravel ride around Trebević', discipline: 'cycling', distance: '32.8 km', duration: '1h 28m', pace: '22.4 km/h' },
  { date: '17 Oct 2026', activity: 'Long run through Vrelo Bosne', discipline: 'running', distance: '14.2 km', duration: '1h 18m', pace: '5:29 /km' }
];
const routeData = [
  { name: 'Vilsonovo šetalište Loop', discipline: 'running', distance: '8.4 km', elevation: '84 m', location: 'Sarajevo, BiH', region: 'Bosnia and Herzegovina' },
  { name: 'Trebević Mountain Gravel', discipline: 'cycling', distance: '32.8 km', elevation: '420 m', location: 'Sarajevo, BiH', region: 'Bosnia and Herzegovina' },
  { name: 'Vrelo Bosne Trail', discipline: 'running', distance: '5.2 km', elevation: '21 m', location: 'Ilidža, BiH', region: 'Bosnia and Herzegovina' },
  { name: 'Mostar to Blagaj Ride', discipline: 'cycling', distance: '41.6 km', elevation: '310 m', location: 'Mostar, BiH', region: 'Balkans' },
  { name: 'Lake Ohrid Lakeside', discipline: 'running', distance: '10.0 km', elevation: '65 m', location: 'Ohrid, North Macedonia', region: 'Balkans' },
  { name: 'Alpe-Adria Trail', discipline: 'cycling', distance: '76.4 km', elevation: '890 m', location: 'Kranjska Gora, Slovenia', region: 'Europe' }
];

document.addEventListener('DOMContentLoaded', () => navigate('dashboard'));

function navigate(page) {
  const isAuthPage = ['login', 'register'].includes(page);
  state.activePage = pages[page] || isAuthPage ? page : 'dashboard';
  document.getElementById('app').innerHTML = isAuthPage ? renderAuth(state.activePage) : renderShell();
  if (!isAuthPage) {
    pages[state.activePage].render(document.getElementById('page-content'));
    document.querySelectorAll('[data-page]').forEach(link => link.addEventListener('click', event => {
      event.preventDefault();
      navigate(link.dataset.page);
    }));
    document.getElementById('logout-link')?.addEventListener('click', event => {
      event.preventDefault();
      showToast('Logout will be connected to JWT authentication in the backend milestone.');
    });
  }
}

function renderShell() {
  const page = pages[state.activePage];
  return `<nav class="navbar navbar-expand-lg navbar-dark"><div class="container-fluid px-4">
    <a class="navbar-brand" href="#" data-page="dashboard"><span class="brand-mark">●</span> EnduroTrack</a>
    <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#main-nav"><span class="navbar-toggler-icon"></span></button>
    <div class="collapse navbar-collapse" id="main-nav"><div class="ms-auto d-flex align-items-center gap-3">
      <span class="text-white-50 small d-none d-md-inline">Good morning, ${state.user.name}</span>
      <a href="#" data-page="login" class="btn btn-sm btn-outline-light">Login preview</a>
      <a href="#" id="logout-link" class="btn btn-sm btn-outline-light">Log out</a>
    </div></div>
  </div></nav>
  <div class="container-fluid"><div class="row">
    <aside class="col-lg-2 p-3 sidebar"><div class="small text-uppercase text-white-50 mb-2">Workspace</div>
      ${navLink('dashboard', 'grid-1x2', 'Dashboard')}
      ${navLink('sessions', 'activity', 'Training sessions')}
      ${navLink('routes', 'signpost-2', 'Routes')}
      ${navLink('plans', 'calendar3', 'Training plans')}
      ${navLink('competitions', 'trophy', 'Competitions')}
      <div class="small text-uppercase text-white-50 mt-4 mb-2">Account</div>
      ${navLink('profile', 'person', 'My profile')}
      <div class="small text-uppercase text-white-50 mt-4 mb-2">Demo views</div>
      ${navLink('coach', 'people', 'Coach workspace')}
      ${navLink('admin', 'shield-check', 'Admin panel')}
    </aside>
    <main class="col-lg-10 p-3 p-md-4"><div class="mb-4"><h1 class="h3 mb-1">${page.title}</h1><p class="text-secondary mb-0">${page.subtitle}</p></div><div id="page-content"></div></main>
  </div></div>`;
}

function navLink(page, icon, label) {
  return `<a href="#" data-page="${page}" class="sidebar-link ${state.activePage === page ? 'active' : ''}"><i class="bi bi-${icon} me-2"></i>${label}</a>`;
}

function renderDashboard(el) {
  el.innerHTML = `<section class="hero p-4 p-md-5 mb-4"><div class="row align-items-center"><div class="col-md-8">
    <span class="badge btn-accent mb-3">THIS WEEK</span><h2 class="display-5">Build momentum,<br>one session at a time.</h2><p class="lead text-white-50">You are 6.2 km away from your weekly goal. Keep going!</p><button class="btn btn-accent" data-page="sessions">Record a session <i class="bi bi-arrow-right ms-2"></i></button>
  </div><div class="col-md-4 text-center d-none d-md-block"><i class="bi bi-bicycle display-1 text-white-50"></i></div></div></section>
  <div class="row g-3 mb-4">${stat('activity', 'Weekly distance', '42.8 km', '+12.4%', true)}${stat('clock', 'Training time', '3h 18m', '+8.1%', true)}${stat('trophy', 'Sessions', '5', '+2 this week', true)}${stat('bullseye', 'Weekly goal', '86%', '6.2 km left', false)}</div>
  <div class="row g-4"><div class="col-xl-8"><div class="card p-4"><div class="d-flex justify-content-between align-items-center mb-3"><h2 class="h5 mb-0">Recent sessions</h2><a href="#" data-page="sessions" class="small text-success">View all</a></div>${sessionTable(demoSessions.slice(0, 3))}</div></div>
  <div class="col-xl-4"><div class="card weather-card p-4 mb-4"><div class="d-flex justify-content-between"><div><p class="small text-secondary mb-1">NEXT TRAINING · SARAJEVO · OPEN-METEO</p><h2 class="h4">Perfect for a ride</h2><p class="mb-0 text-secondary">Thursday, 24 October</p></div><i class="bi bi-sun fs-1 text-warning"></i></div><hr><div class="d-flex justify-content-between"><span>Temperature</span><strong>18°C</strong><span>Wind 9 km/h</span></div></div><div class="card p-4"><h2 class="h5">Upcoming competition</h2><p class="text-secondary small">Green Hills Gran Fondo</p><div class="d-flex justify-content-between"><strong>03 Nov 2026</strong><span class="badge badge-cycling">Cycling</span></div></div></div></div>`;
  bindPageLinks(el);
}

function stat(icon, label, value, change, positive) {
  return `<div class="col-sm-6 col-xl-3"><div class="card stat-card p-3"><div class="d-flex justify-content-between"><div><p class="text-secondary small mb-2">${label}</p><h3 class="h4 mb-2">${value}</h3><span class="small ${positive ? 'text-success' : 'text-warning'}"><i class="bi bi-arrow-${positive ? 'up' : 'right'}"></i> ${change}</span></div><div class="stat-icon"><i class="bi bi-${icon}"></i></div></div></div></div>`;
}

function renderSessions(el) {
  el.innerHTML = `<div class="d-flex justify-content-between align-items-center mb-3"><p class="text-secondary mb-0">3 sessions in October</p><button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#session-modal"><i class="bi bi-plus-lg me-2"></i>Record session</button></div><div class="card p-2 p-md-3">${sessionTable(demoSessions.concat([{ date: '14 Oct 2026', activity: 'Tempo run', discipline: 'running', distance: '7.4 km', duration: '38 min', pace: '5:08 /km' }]))}</div>${sessionModal()}`;
  document.getElementById('save-session')?.addEventListener('click', () => { bootstrap.Modal.getInstance(document.getElementById('session-modal')).hide(); showToast('Demo session saved locally.'); });
}

function sessionTable(items) {
  return `<div class="table-responsive"><table class="table align-middle mb-0"><thead><tr><th>Date</th><th>Activity</th><th>Distance</th><th>Duration</th><th>Metric</th></tr></thead><tbody>${items.map(item => `<tr><td class="text-secondary">${item.date}</td><td><span class="badge ${item.discipline === 'running' ? 'badge-running' : 'badge-cycling'} me-2">${item.discipline}</span>${item.activity}</td><td class="fw-semibold">${item.distance}</td><td>${item.duration}</td><td>${item.pace}</td></tr>`).join('')}</tbody></table></div>`;
}

function sessionModal() {
  return `<div class="modal fade" id="session-modal" tabindex="-1"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><h2 class="modal-title h5">Record training session</h2><button class="btn-close" data-bs-dismiss="modal"></button></div><div class="modal-body"><label class="form-label">Discipline</label><select class="form-select mb-3"><option>Running</option><option>Cycling</option></select><label class="form-label">Distance (km)</label><input class="form-control mb-3" type="number" placeholder="10.5"><label class="form-label">Duration</label><input class="form-control mb-3" type="text" placeholder="00:52:00"><label class="form-label">Date</label><input class="form-control" type="date"></div><div class="modal-footer"><button class="btn btn-light" data-bs-dismiss="modal">Cancel</button><button class="btn btn-primary" id="save-session">Save session</button></div></div></div></div>`;
}

function renderRoutes(el) {
  el.innerHTML = `<div class="d-flex justify-content-between align-items-center mb-3"><p class="text-secondary">Routes from Bosnia and Herzegovina, the Balkans and Europe.</p><button class="btn btn-primary" onclick="showToast('Route form will use the backend in the next milestone.')"><i class="bi bi-plus-lg me-2"></i>Add route</button></div><div class="row g-3">${routeData.map(route => `<div class="col-md-6 col-xl-4"><div class="card route-card p-4 h-100"><div class="d-flex justify-content-between"><span class="badge ${route.discipline === 'running' ? 'badge-running' : 'badge-cycling'}">${route.discipline}</span><span class="small text-secondary">${route.region}</span></div><h2 class="h5 mt-3">${route.name}</h2><p class="text-secondary small"><i class="bi bi-geo-alt me-1"></i>${route.location}</p><div class="d-flex gap-4 mt-3"><div><small class="text-secondary d-block">Distance</small><strong>${route.distance}</strong></div><div><small class="text-secondary d-block">Elevation</small><strong>${route.elevation}</strong></div></div></div></div>`).join('')}</div>`;
}

function renderPlans(el) {
  el.innerHTML = `<div class="card p-4"><div class="d-flex justify-content-between mb-3"><h2 class="h5">This week's plan</h2><button class="btn btn-primary btn-sm" onclick="showToast('Training plan form will be connected to the API later.')">Create plan</button></div><div class="list-group list-group-flush">${['Recovery run · 5 km', 'Intervals · 8 km', 'Rest day', 'Endurance ride · 45 km'].map((item, index) => `<div class="list-group-item px-0 d-flex justify-content-between align-items-center"><div><span class="text-secondary small d-block">${['Mon, 21 Oct', 'Wed, 23 Oct', 'Thu, 24 Oct', 'Sat, 26 Oct'][index]}</span><strong>${item}</strong></div><span class="badge ${index < 2 ? 'text-bg-success' : 'text-bg-light'}">${index < 2 ? 'Completed' : 'Planned'}</span></div>`).join('')}</div></div>`;
}

function renderCompetitions(el) {
  const comps = [['Green Hills Gran Fondo', 'Cycling', '03 Nov 2026', 'Sarajevo, BiH', '120 km'], ['City Night Run', 'Running', '16 Nov 2026', 'Mostar, BiH', '10 km'], ['Winter Trail Challenge', 'Running', '08 Dec 2026', 'Bjelašnica, BiH', '21 km'], ['Skopje-Vodno Challenge', 'Running', '22 Mar 2027', 'Skopje, North Macedonia', '15 km'], ['Dubrovnik Coastal Ride', 'Cycling', '18 Apr 2027', 'Dubrovnik, Croatia', '85 km'], ['Vienna Danube Marathon', 'Running', '10 May 2027', 'Vienna, Austria', '42 km']];
  el.innerHTML = `<div class="row g-3">${comps.map(comp => `<div class="col-md-6 col-xl-4"><div class="card p-4 h-100"><div class="d-flex justify-content-between"><span class="badge ${comp[1] === 'Running' ? 'badge-running' : 'badge-cycling'}">${comp[1]}</span><i class="bi bi-bookmark"></i></div><h2 class="h5 mt-3">${comp[0]}</h2><p class="text-secondary small mb-1"><i class="bi bi-calendar3 me-2"></i>${comp[2]}</p><p class="text-secondary small"><i class="bi bi-geo-alt me-2"></i>${comp[3]}</p><div class="mt-auto pt-3 border-top d-flex justify-content-between align-items-center"><strong>${comp[4]}</strong><button class="btn btn-sm btn-outline-success" onclick="showToast('Registration saved in demo mode.')">Register</button></div></div></div>`).join('')}</div>`;
}

function renderProfile(el) {
  el.innerHTML = `<div class="row g-4"><div class="col-lg-4"><div class="card p-4 text-center"><div class="rounded-circle bg-success text-white d-inline-grid mx-auto mb-3" style="width:80px;height:80px;place-items:center;font-size:2rem">AM</div><h2 class="h5">Dzevad Hrnjic</h2><p class="text-secondary">Runner & cyclist</p><span class="badge badge-running mx-auto">Athlete</span></div></div><div class="col-lg-8"><div class="card p-4"><h2 class="h5 mb-4">Personal information</h2><div class="row g-3"><div class="col-md-6"><label class="form-label">First name</label><input class="form-control" value="Dzevad"></div><div class="col-md-6"><label class="form-label">Last name</label><input class="form-control" value="Hrnjic"></div><div class="col-12"><label class="form-label">Email</label><input class="form-control" value="athlete@endurotrack.test" type="email"></div><div class="col-md-6"><label class="form-label">Main discipline</label><select class="form-select"><option>Both running and cycling</option><option>Running</option><option>Cycling</option></select></div><div class="col-md-6"><label class="form-label">Experience</label><select class="form-select"><option>Intermediate</option><option>Beginner</option><option>Advanced</option></select></div><div class="col-12"><label class="form-label">Season goal</label><textarea class="form-control" rows="3">Complete my first 100 km cycling event.</textarea></div><div class="col-12"><button class="btn btn-primary" onclick="showToast('Profile changes saved in demo mode.')">Save changes</button></div></div></div></div></div>`;
}

function renderCoach(el) {
  el.innerHTML = `<div class="alert alert-success"><i class="bi bi-info-circle me-2"></i>This demo view shows the planned coach role. Backend authorization will restrict this section to coaches.</div><div class="row g-3">${stat('people', 'Active athletes', '24', '+3 this month', true)}${stat('calendar-check', 'Planned sessions', '38', 'This week', true)}${stat('graph-up-arrow', 'Avg. adherence', '87%', '+4.2%', true)}</div><div class="card p-4 mt-4"><h2 class="h5">Athlete overview</h2><div class="table-responsive"><table class="table"><thead><tr><th>Athlete</th><th>Discipline</th><th>Weekly distance</th><th>Status</th></tr></thead><tbody><tr><td>Dzevad Hrnjic</td><td>Running & cycling</td><td>42.8 km</td><td><span class="badge text-bg-success">On track</span></td></tr><tr><td>Sam Wilson</td><td>Running</td><td>26.1 km</td><td><span class="badge text-bg-warning">Needs attention</span></td></tr></tbody></table></div></div>`;
}

function renderAdmin(el) {
  el.innerHTML = `<div class="alert alert-warning"><i class="bi bi-shield-lock me-2"></i>Admin-only demo panel. Real role checks will be enforced by the FlightPHP API.</div><div class="row g-3">${stat('people', 'Registered users', '148', '+12 this month', true)}${stat('signpost-2', 'Saved routes', '63', '+8 this month', true)}${stat('trophy', 'Competitions', '17', '4 upcoming', false)}</div><div class="card p-4 mt-4"><h2 class="h5">Platform management</h2><div class="list-group list-group-flush"><a class="list-group-item list-group-item-action px-0" href="#" onclick="showToast('User management is planned for the API milestone.')"><i class="bi bi-people me-2"></i>Manage users <i class="bi bi-chevron-right float-end"></i></a><a class="list-group-item list-group-item-action px-0" href="#" onclick="showToast('System logs will be available after backend setup.')"><i class="bi bi-file-earmark-text me-2"></i>View system logs <i class="bi bi-chevron-right float-end"></i></a></div></div>`;
}

function renderAuth(mode) {
  const isLogin = mode === 'login';
  return `<div class="auth-page d-flex align-items-center p-3"><div class="auth-card w-100"><div class="text-center text-white mb-4"><h1 class="h2"><span class="brand-mark">●</span> EnduroTrack</h1><p class="text-white-50">Train smarter. Track progress.</p></div><div class="card p-4 p-md-5"><h2 class="h4">${isLogin ? 'Welcome back' : 'Create your account'}</h2><p class="text-secondary">${isLogin ? 'Sign in to your training workspace.' : 'Start tracking your running and cycling progress.'}</p>${isLogin ? '' : '<div class="row g-2"><div class="col-6"><label class="form-label mt-3">First name</label><input class="form-control" placeholder="Dzevad"></div><div class="col-6"><label class="form-label mt-3">Last name</label><input class="form-control" placeholder="Hrnjic"></div></div>'}<label class="form-label mt-3">Email</label><input class="form-control mb-3" type="email" placeholder="you@example.com"><label class="form-label">Password</label><input class="form-control mb-4" type="password" placeholder="••••••••">${isLogin ? '' : '<label class="form-label">Main discipline</label><select class="form-select mb-4"><option>Running and cycling</option><option>Running</option><option>Cycling</option></select>'}<button class="btn btn-primary w-100" onclick="navigate('dashboard')">${isLogin ? 'Sign in' : 'Create account'}</button><p class="text-center text-secondary small mt-4 mb-0">${isLogin ? 'New to EnduroTrack?' : 'Already have an account?'} <a href="#" onclick="navigate('${isLogin ? 'register' : 'login'}'); return false;">${isLogin ? 'Create an account' : 'Sign in'}</a></p></div></div></div>`;
}

function bindPageLinks(el) {
  el.querySelectorAll('[data-page]').forEach(link => link.addEventListener('click', event => { event.preventDefault(); navigate(link.dataset.page); }));
}

function showToast(message) {
  const container = document.getElementById('toast-container');
  const toast = document.createElement('div');
  toast.className = 'toast align-items-center text-bg-dark border-0';
  toast.innerHTML = `<div class="d-flex"><div class="toast-body">${message}</div><button class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button></div>`;
  container.appendChild(toast);
  const instance = new bootstrap.Toast(toast, { delay: 2800 });
  instance.show();
  toast.addEventListener('hidden.bs.toast', () => toast.remove());
}
