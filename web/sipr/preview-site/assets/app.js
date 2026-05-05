const sidebar = document.getElementById('sidebar');
const overlay = document.getElementById('overlay');
const sidebarToggle = document.getElementById('sidebarToggle');

sidebarToggle?.addEventListener('click', () => {
  sidebar?.classList.add('open');
  overlay?.classList.add('visible');
});

overlay?.addEventListener('click', () => {
  sidebar?.classList.remove('open');
  overlay?.classList.remove('visible');
});

document.querySelectorAll('.wizard-shell').forEach((shell) => {
  const tabs = Array.from(shell.querySelectorAll('.wizard-tab'));
  const panels = Array.from(shell.querySelectorAll('.wizard-panel'));
  const prevButton = shell.querySelector('.wizard-prev');
  const nextButton = shell.querySelector('.wizard-next');
  const submitButton = shell.querySelector('.wizard-submit');
  let currentStep = 1;

  const renderStep = () => {
    tabs.forEach((tab, index) => tab.classList.toggle('active', index + 1 === currentStep));
    panels.forEach((panel) => panel.classList.toggle('active', Number(panel.dataset.stepPanel) === currentStep));
    if (prevButton) prevButton.style.display = currentStep === 1 ? 'none' : 'inline-flex';
    if (nextButton) nextButton.style.display = currentStep === 4 ? 'none' : 'inline-flex';
    if (submitButton) submitButton.style.display = currentStep === 4 ? 'inline-flex' : 'none';
  };

  tabs.forEach((tab) => tab.addEventListener('click', () => {
    currentStep = Number(tab.dataset.step);
    renderStep();
  }));

  prevButton?.addEventListener('click', () => {
    currentStep = Math.max(1, currentStep - 1);
    renderStep();
  });

  nextButton?.addEventListener('click', () => {
    currentStep = Math.min(4, currentStep + 1);
    renderStep();
  });

  renderStep();
});

const loginMode = document.getElementById('loginMode');
if (loginMode) {
  const demoFields = document.querySelectorAll('.demo-only-field');
  const authFields = document.querySelectorAll('.auth-only-field');
  const summary = document.getElementById('modeSummaryText');

  const applyLoginMode = () => {
    const isDemo = loginMode.value === 'demo';

    demoFields.forEach((element) => {
      element.style.display = isDemo ? '' : 'none';
    });

    authFields.forEach((element) => {
      element.style.display = isDemo ? 'none' : '';
    });

    if (summary) {
      summary.textContent = isDemo
        ? 'Gunakan nama dan role untuk simulasi cepat tanpa bergantung pada tabel users.'
        : 'Gunakan email dan password pengguna Laravel. Role aktif akan dibaca dari akun yang berhasil login.';
    }
  };

  loginMode.addEventListener('change', applyLoginMode);
  applyLoginMode();
}
