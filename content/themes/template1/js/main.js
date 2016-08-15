function loadPage()
{
    if (document.login)//if the form login exists, focus:
    {
        document.login.name.focus();//the username input
        document.login.pass.focus();//the password input
        document.login.login.focus();//the login button (submitbutton)
    }
}