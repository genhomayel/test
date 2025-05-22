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
    <title>Politique de Confidentialité - StachZer</title>
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
                <h1 class="text-3xl font-bold mb-6 text-center">Politique de Confidentialité</h1>
                <p class="text-sm text-gray-400 mb-8 text-center">Dernière mise à jour : <?php echo date('d/m/Y'); ?></p>

                <div class="prose prose-lg prose-invert max-w-none">
                    <section class="mb-8">
                        <p>Chez StachZer, nous accordons une grande importance à la protection de vos données personnelles. Cette politique de confidentialité vous informe sur la façon dont nous collectons, utilisons et protégeons vos informations lorsque vous utilisez notre site web.</p>
                    </section>

                    <section class="mb-8">
                        <h2 class="text-xl font-semibold mb-4">1. Collecte d'informations</h2>
                        <p>Nous collectons les types d'informations suivants :</p>
                        <ul class="list-disc pl-6 mb-4 space-y-2">
                            <li><strong>Informations de navigation</strong> : Lorsque vous visitez notre site, nous enregistrons automatiquement certaines informations techniques telles que votre adresse IP, le type de navigateur utilisé, les pages visitées, et le temps passé sur chaque page.</li>
                            <li><strong>Données de compte</strong> : Pour les utilisateurs du panneau d'administration, nous collectons votre nom d'utilisateur et votre adresse e-mail. Vos mots de passe sont stockés de manière sécurisée sous forme hachée.</li>
                            <li><strong>Contenu communautaire</strong> : Si vous contribuez au site en soumettant des highlights ou des commentaires, nous collectons les informations que vous fournissez, y compris votre nom d'auteur et vos initiales.</li>
                        </ul>
                    </section>

                    <section class="mb-8">
                        <h2 class="text-xl font-semibold mb-4">2. Utilisation des cookies</h2>
                        <p>Notre site utilise des cookies et technologies similaires pour améliorer votre expérience. Nous utilisons les types de cookies suivants :</p>
                        <ul class="list-disc pl-6 mb-4 space-y-2">
                            <li><strong>Cookies essentiels</strong> : Nécessaires au fonctionnement du site (ex. PHPSESSID pour gérer les sessions).</li>
                            <li><strong>Cookies fonctionnels</strong> : Permettent de mémoriser vos préférences (ex. filtres de recherche).</li>
                            <li><strong>Cookies analytiques</strong> : Nous aident à comprendre comment les visiteurs interagissent avec le site afin de l'améliorer.</li>
                        </ul>
                        <p>Vous pouvez configurer votre navigateur pour refuser tous les cookies ou vous alerter lorsqu'un cookie est envoyé. Cependant, certaines fonctionnalités du site peuvent ne pas fonctionner correctement sans cookies.</p>
                    </section>
                    
                    <section class="mb-8">
                        <h2 class="text-xl font-semibold mb-4">3. Utilisation des informations</h2>
                        <p>Les informations que nous collectons sont utilisées pour :</p>
                        <ul class="list-disc pl-6 mb-4 space-y-2">
                            <li>Améliorer et personnaliser votre expérience sur notre site</li>
                            <li>Gérer votre compte d'administration le cas échéant</li>
                            <li>Afficher le contenu communautaire sur le site</li>
                            <li>Analyser l'utilisation du site pour l'améliorer</li>
                            <li>Communiquer avec vous concernant des mises à jour ou des notifications importantes</li>
                        </ul>
                    </section>

                    <section class="mb-8">
                        <h2 class="text-xl font-semibold mb-4">4. Partage d'informations</h2>
                        <p>Nous ne vendons, n'échangeons ni ne transférons vos informations personnelles à des tiers, sauf dans les cas suivants :</p>
                        <ul class="list-disc pl-6 mb-4 space-y-2">
                            <li>Lorsque cela est nécessaire pour fournir un service que vous avez demandé</li>
                            <li>Pour nous conformer à une obligation légale</li>
                            <li>Pour protéger nos droits, notre propriété ou notre sécurité</li>
                        </ul>
                    </section>
                    
                    <section class="mb-8">
                        <h2 class="text-xl font-semibold mb-4">5. Sécurité des données</h2>
                        <p>Nous prenons des mesures de sécurité appropriées pour protéger vos informations personnelles contre l'accès non autorisé, la modification, la divulgation ou la destruction. Ces mesures comprennent le hachage des mots de passe, l'utilisation de connexions sécurisées (HTTPS) et des restrictions d'accès aux informations personnelles.</p>
                    </section>
                    
                    <section class="mb-8">
                        <h2 class="text-xl font-semibold mb-4">6. Conservation des données</h2>
                        <p>Nous conservons vos données personnelles aussi longtemps que nécessaire pour les finalités décrites dans cette politique de confidentialité. Les données de navigation sont généralement conservées pour une durée maximale de 12 mois.</p>
                    </section>
                    
                    <section class="mb-8">
                        <h2 class="text-xl font-semibold mb-4">7. Vos droits</h2>
                        <p>Conformément au Règlement Général sur la Protection des Données (RGPD), vous disposez des droits suivants concernant vos données personnelles :</p>
                        <ul class="list-disc pl-6 mb-4 space-y-2">
                            <li>Droit d'accès à vos données</li>
                            <li>Droit de rectification des données inexactes</li>
                            <li>Droit à l'effacement de vos données</li>
                            <li>Droit à la limitation du traitement</li>
                            <li>Droit à la portabilité des données</li>
                            <li>Droit d'opposition au traitement</li>
                        </ul>
                        <p>Pour exercer ces droits, veuillez nous contacter à l'adresse indiquée dans la section "Contact".</p>
                    </section>
                    
                    <section class="mb-8">
                        <h2 class="text-xl font-semibold mb-4">8. Services tiers</h2>
                        <p>Notre site peut inclure des contenus intégrés provenant d'autres services (par exemple, vidéos YouTube ou Twitch, Google Fonts). Ces services peuvent collecter des données vous concernant, utiliser des cookies et vous suivre. Nous vous recommandons de consulter les politiques de confidentialité de ces services pour plus d'informations.</p>
                    </section>
                    
                    <section class="mb-8">
                        <h2 class="text-xl font-semibold mb-4">9. Modifications de la politique de confidentialité</h2>
                        <p>Nous nous réservons le droit de modifier cette politique de confidentialité à tout moment. Toute modification sera publiée sur cette page avec la date de mise à jour. Nous vous encourageons à consulter régulièrement cette page pour rester informé des changements.</p>
                    </section>
                    
                    <section>
                        <h2 class="text-xl font-semibold mb-4">10. Contact</h2>
                        <p>Si vous avez des questions concernant cette politique de confidentialité ou vos données personnelles, veuillez nous contacter à : <a href="mailto:contact@stachzer.com" class="text-pink-300 hover:underline">contact@stachzer.com</a></p>
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
