<?php
// Inclure les fichiers nécessaires
if (file_exists('includes/config.php')) {
    require_once 'includes/config.php';
    require_once 'includes/db.php';
    require_once 'includes/functions.php';
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Conditions Générales d'Utilisation - StachZer</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <!-- Navigation Header -->
    <header class="fixed top-0 left-0 right-0 z-50 bg-opacity-90 bg-gray-900 backdrop-filter backdrop-blur-lg border-b border-gray-800">
        <div class="container mx-auto px-4 py-3">
            <div class="flex justify-between items-center">
                <div class="flex items-center">
                    <div class="text-2xl font-extrabold mr-6">
                        <a href="index.php"><span class="text-white">Stach</span><span style="color: var(--pink-light)">Zer</span></a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="pt-16">
        <div class="container mx-auto px-4 py-10">
            <div class="max-w-4xl mx-auto bg-gray-800 rounded-lg p-6">
                <h1 class="text-3xl font-bold mb-6 text-center">Conditions Générales d'Utilisation</h1>
                <p class="text-sm text-gray-400 mb-8 text-center">Dernière mise à jour : <?php echo date('d/m/Y'); ?></p>

                <div class="prose prose-lg prose-invert max-w-none">
                    <section class="mb-8">
                        <h2 class="text-xl font-semibold mb-4">1. Acceptation des conditions</h2>
                        <p>En accédant et en utilisant le site StachZer.com (ci-après "le Site"), vous acceptez d'être lié par les présentes Conditions Générales d'Utilisation. Si vous n'acceptez pas ces conditions, veuillez ne pas utiliser le Site.</p>
                    </section>

                    <section class="mb-8">
                        <h2 class="text-xl font-semibold mb-4">2. Description du service</h2>
                        <p>StachZer.com est une plateforme communautaire dédiée au jeu Call of Duty: Warzone. Le Site propose des informations sur les armes, les stratégies, ainsi qu'un espace communautaire pour partager des highlights et des conseils liés au jeu.</p>
                    </section>

                    <section class="mb-8">
                        <h2 class="text-xl font-semibold mb-4">3. Contenu et propriété intellectuelle</h2>
                        <p>3.1. Le contenu du Site, incluant sans limitation les textes, graphiques, images, logos, icônes, et code source, est la propriété de StachZer et est protégé par les lois sur la propriété intellectuelle.</p>
                        <p>3.2. Les noms et les éléments liés à Call of Duty® et Warzone™ sont des marques déposées d'Activision Publishing, Inc. Le Site n'est pas affilié, associé, autorisé, approuvé par ou lié de quelque façon que ce soit à Activision Publishing, Inc.</p>
                        <p>3.3. Toute reproduction, distribution, ou utilisation non autorisée du contenu du Site est strictement interdite.</p>
                    </section>
                    
                    <section class="mb-8">
                        <h2 class="text-xl font-semibold mb-4">4. Contributions des utilisateurs</h2>
                        <p>4.1. Les utilisateurs peuvent soumettre du contenu sous forme de highlights ou commentaires. En soumettant ce contenu, vous accordez à StachZer une licence mondiale, non exclusive, gratuite, transférable et sous-licenciable pour utiliser, reproduire, distribuer, et afficher ce contenu sur le Site.</p>
                        <p>4.2. Vous garantissez que tout contenu que vous soumettez ne viole pas les droits de propriété intellectuelle d'un tiers, n'est pas diffamatoire, obscène, offensant, ou illégal de quelque manière que ce soit.</p>
                        <p>4.3. StachZer se réserve le droit de modérer, refuser ou supprimer tout contenu soumis par les utilisateurs à sa seule discrétion.</p>
                    </section>

                    <section class="mb-8">
                        <h2 class="text-xl font-semibold mb-4">5. Comportement des utilisateurs</h2>
                        <p>5.1. Les utilisateurs s'engagent à ne pas:</p>
                        <ul class="list-disc pl-6 mb-4 space-y-2">
                            <li>Publier du contenu illégal, abusif, harcelant, ou inapproprié</li>
                            <li>Tenter de compromettre la sécurité du Site ou d'accéder à des zones restreintes</li>
                            <li>Utiliser le Site pour distribuer des logiciels malveillants ou effectuer des attaques informatiques</li>
                            <li>Collecter des informations sur d'autres utilisateurs sans leur consentement</li>
                            <li>Utiliser le Site d'une manière qui pourrait endommager, désactiver, surcharger ou altérer son fonctionnement</li>
                        </ul>
                    </section>

                    <section class="mb-8">
                        <h2 class="text-xl font-semibold mb-4">6. Limitation de responsabilité</h2>
                        <p>6.1. Le Site est fourni "tel quel" et "tel que disponible", sans garantie d'aucune sorte.</p>
                        <p>6.2. StachZer ne garantit pas que le Site sera ininterrompu, sécurisé, ou exempt d'erreurs.</p>
                        <p>6.3. StachZer ne sera pas responsable des dommages directs, indirects, accessoires, spéciaux ou consécutifs résultant de l'utilisation ou de l'impossibilité d'utiliser le Site.</p>
                    </section>
                    
                    <section class="mb-8">
                        <h2 class="text-xl font-semibold mb-4">7. Liens externes</h2>
                        <p>Le Site peut contenir des liens vers des sites web tiers. Ces liens sont fournis uniquement pour votre commodité. StachZer n'a aucun contrôle sur le contenu de ces sites et n'assume aucune responsabilité pour le contenu, les politiques de confidentialité ou les pratiques de tout site tiers.</p>
                    </section>
                    
                    <section class="mb-8">
                        <h2 class="text-xl font-semibold mb-4">8. Modification des conditions</h2>
                        <p>StachZer se réserve le droit de modifier ces Conditions Générales d'Utilisation à tout moment. Les modifications entreront en vigueur dès leur publication sur le Site. Il est de votre responsabilité de consulter régulièrement ces conditions.</p>
                    </section>
                    
                    <section class="mb-8">
                        <h2 class="text-xl font-semibold mb-4">9. Droit applicable et juridiction</h2>
                        <p>Les présentes Conditions Générales d'Utilisation sont régies par le droit français. Tout litige relatif à l'interprétation ou à l'exécution de ces conditions sera soumis à la compétence exclusive des tribunaux français.</p>
                    </section>
                    <section>
    <h2 class="text-xl font-semibold mb-4">10. Contenus générés par intelligence artificielle</h2>
    <p>
        Certaines images ou éléments visuels présents sur le site <strong>StachZer</strong> peuvent être générés ou modifiés à l’aide de technologies d’intelligence artificielle (IA). Ces contenus sont utilisés à des fins illustratives, artistiques ou expérimentales, notamment dans le cadre de présentations, d'interfaces ou d'éléments visuels du jeu ou de la plateforme.
    </p>
    <p class="mt-2">
        Bien que nous nous efforcions de garantir la cohérence, la qualité et le bon usage de ces images, celles-ci ne doivent pas être interprétées comme des représentations fidèles de la réalité. Toute ressemblance avec des personnes existantes, réelles ou fictives, serait purement fortuite.
    </p>
    <p class="mt-2">
        Les droits relatifs à ces contenus générés ou modifiés par IA sont réservés à <strong>StachZer</strong> ou à ses partenaires. Toute reproduction, modification ou réutilisation sans autorisation écrite préalable est strictement interdite.
    </p>
</section>

                    <section>
                        <h2 class="text-xl font-semibold mb-4">11. Contact</h2>
                        <p>Pour toute question concernant ces Conditions Générales d'Utilisation, veuillez contacter : <a href="mailto:contact@stachzer.com" class="text-pink-300 hover:underline">contact@stachzer.com</a></p>
                    </section>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-gray-900 py-8 border-t border-gray-800">
        <div class="container mx-auto px-4">
            <div class="text-center text-sm text-gray-400">
                <div class="mb-2">© <?php echo date('Y'); ?> StachZer.com. Tous droits réservés.</div>
                <div>
                    <a href="confidentialite.php" class="hover:text-pink-300 transition mr-4">Politique de confidentialité</a>
                    <a href="cgu.php" class="hover:text-pink-300 transition">Conditions d'utilisation</a>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>
