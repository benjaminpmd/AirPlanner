
<section class="mx-10 md:m-auto md:max-w-3xl p-5 text-center rounded-xl items-center object-center bg-gray-200 dark:bg-gray-700">
    <h2 class="p-2 text-3xl">Vous n'êtes pas connecté</h2>
    <article>
        <h3 class="p-2 pb-4 text-xl">Ce service requiert d'être connecté avec un compte <?php if($page_canonical == "/mechanic.php") echo "mécanicien"; else echo "pilote"?></h3>
        <a 
            class="p-2 m-4 rounded-full bg-sky-300 dark:bg-sky-700 duration-300 hover:bg-sky-400 dark:hover:bg-sky-600"
            href="login.php"
        >
            Se connecter
        </a>
    </article>
    <article>
        <h3 class="p-2 pb-4 text-xl">Vous n'êtes pas encore enregistré ?</h3>
        <a 
            class="p-2 rounded-full bg-sky-300 dark:bg-sky-700 duration-300 hover:bg-sky-400 dark:hover:bg-sky-600"
            href="register.php"
        >
            S'enregistrer
        </a>
    </article>
</section>