const adminLoginButton = document.getElementById('adminLogin');
const studentLoginButton = document.getElementById('studentLogin');
const container = document.getElementById('container');

studentLoginButton.addEventListener('click', () => {
	container.classList.add("right-panel-active");
});
adminLoginButton.addEventListener('click', () => {
	container.classList.remove("right-panel-active");
});