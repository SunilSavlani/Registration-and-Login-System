function validateRegisterForm() {
  const username = document.forms["registerForm"]["username"].value.trim();
  const email = document.forms["registerForm"]["email"].value.trim();
  const password = document.forms["registerForm"]["password"].value;
  const confirmPassword = document.forms["registerForm"]["confirm_password"].value;

  if (!username || !email || !password || !confirmPassword) {
    alert("All fields are required.");
    return false;
  }

  if (password !== confirmPassword) {
    alert("Passwords do not match.");
    return false;
  }

  const emailPattern = /\S+@\S+\.\S+/;
  if (!emailPattern.test(email)) {
    alert("Please enter a valid email address.");
    return false;
  }

  return true;
}

function validateLoginForm() {
  const username = document.forms["loginForm"]["username"].value.trim();
  const password = document.forms["loginForm"]["password"].value;

  if (!username || !password) {
    alert("Please enter both username and password.");
    return false;
  }

  return true;
}
