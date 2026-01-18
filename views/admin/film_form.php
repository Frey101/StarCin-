<?php require ROOT_DIR . 'views/layout/header.php'; ?>

<div class="admin-container">
    <!-- SIDEBAR -->
    <aside class="sidebar">
        <div class="sidebar-header">
            <h2>Administration</h2>
            <p class="admin-welcome">Bienvenue, <?php echo htmlspecialchars($_SESSION['user_email'] ?? 'Admin'); ?></p>
        </div>
        <nav class="sidebar-nav">
            <a href="<?php echo ROOT_PATH; ?>index.php?action=admin_dashboard" class="nav-link">
                Tableau de bord
            </a>
            <a href="<?php echo ROOT_PATH; ?>index.php?action=admin_list_films" class="nav-link">
                Gérer les Films
            </a>
            <a href="<?php echo ROOT_PATH; ?>index.php?action=admin_users" class="nav-link">
                Utilisateurs
            </a>
            <a href="<?php echo ROOT_PATH; ?>index.php?action=admin_propositions" class="nav-link">
                Propositions
            </a>
        </nav>
        <div class="sidebar-footer">
            <a href="<?php echo ROOT_PATH; ?>index.php?action=home" class="nav-link">
                Retour au site
            </a>

        </div>
    </aside>

    <!-- CONTENT -->
    <main class="content">
        <div class="content-header">
            <h1><?php echo $film->getIdFilm() ? 'Modifier un film' : 'Ajouter un film'; ?></h1>
            <div class="breadcrumb">
                <a href="<?php echo ROOT_PATH; ?>index.php?action=admin_dashboard">Tableau de bord</a> / 
                <a href="<?php echo ROOT_PATH; ?>index.php?action=admin_list_films">Films</a> / 
                <span><?php echo $film->getIdFilm() ? 'Modifier' : 'Ajouter'; ?></span>
            </div>
        </div>

        <?php if (isset($message) && $message): ?>
            <div class="alert alert-error">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($errors) && !empty($errors)): ?>
            <div class="alert alert-error">
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo htmlspecialchars($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <section class="admin-section">
            <form method="POST" action="<?php echo ROOT_PATH; ?>index.php?action=admin_handle_film_form<?php echo $film->getIdFilm() ? '&id=' . $film->getIdFilm() : ''; ?>" enctype="multipart/form-data">
                <?php if ($film->getIdFilm()): ?>
                    <input type="hidden" name="id_film" value="<?php echo $film->getIdFilm(); ?>">
                <?php endif; ?>

                <div class="form-group">
                    <label for="title">Titre *</label>
                    <input type="text" id="title" name="title" 
                           value="<?php echo htmlspecialchars($film->getTitre()); ?>" 
                           required>
                </div>

                <div class="form-group">
                    <label for="synopsis">Synopsis *</label>
                    <textarea id="synopsis" name="synopsis" rows="5" required><?php echo htmlspecialchars($film->getSynopsis()); ?></textarea>
                </div>

                <div class="form-group">
                    <label for="image_file">Image du film *</label>
                    <input type="file" id="image_file" name="image_file" 
                           accept="image/jpeg,image/jpg,image/png,image/gif,image/webp"
                           <?php echo !$film->getIdFilm() ? 'required' : ''; ?>>
                    <small style="color: rgba(255,255,255,0.6); font-size: 12px; display: block; margin-top: 5px;">
                        Formats acceptés: JPG, PNG, GIF, WebP (max 5MB)
                    </small>
                    <div id="imagePreview" style="margin-top: 15px; display: none;">
                        <p style="color: rgba(255,255,255,0.8); font-size: 14px; margin: 5px 0;">Aperçu de la nouvelle image:</p>
                        <img id="previewImg" src="" alt="Aperçu" 
                             style="max-width: 200px; max-height: 200px; border-radius: 8px; border: 2px solid rgba(255,255,255,0.2);">
                    </div>
                    <?php 
                    // Afficher l'image actuelle si le film existe
                    if ($film->getIdFilm()): 
                        $currentImagePath = ROOT_PATH . 'public/image/film_' . $film->getIdFilm() . '.jpg';
                        $imageExists = false;
                        $extensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                        foreach ($extensions as $ext) {
                            $testPath = ROOT_DIR . 'public/image/film_' . $film->getIdFilm() . '.' . $ext;
                            if (file_exists($testPath)) {
                                $currentImagePath = ROOT_PATH . 'public/image/film_' . $film->getIdFilm() . '.' . $ext;
                                $imageExists = true;
                                break;
                            }
                        }
                        if ($imageExists):
                    ?>
                        <div style="margin-top: 15px;">
                            <p style="color: rgba(255,255,255,0.8); font-size: 14px; margin: 5px 0;">Image actuelle:</p>
                            <img src="<?php echo htmlspecialchars($currentImagePath); ?>" 
                                 alt="Image actuelle" 
                                 style="max-width: 200px; max-height: 200px; border-radius: 8px; border: 2px solid rgba(255,255,255,0.2);">
                        </div>
                    <?php endif; endif; ?>
                </div>

                <script>
                    // Aperçu de l'image avant upload
                    document.getElementById('image_file')?.addEventListener('change', function(e) {
                        const file = e.target.files[0];
                        if (file) {
                            const reader = new FileReader();
                            reader.onload = function(e) {
                                const preview = document.getElementById('imagePreview');
                                const previewImg = document.getElementById('previewImg');
                                if (preview && previewImg) {
                                    previewImg.src = e.target.result;
                                    preview.style.display = 'block';
                                }
                            };
                            reader.readAsDataURL(file);
                        } else {
                            const preview = document.getElementById('imagePreview');
                            if (preview) {
                                preview.style.display = 'none';
                            }
                        }
                    });
                </script>

                <div class="form-row">
                    <div class="form-group">
                        <label for="categorie">Catégorie</label>
                        <input type="text" id="categorie" name="categorie" 
                               value="<?php echo htmlspecialchars($film->getCategorie() ?? ''); ?>">
                    </div>

                    <div class="form-group">
                        <label for="release_year">Année de sortie *</label>
                        <input type="number" id="release_year" name="release_year" 
                               value="<?php echo htmlspecialchars($film->getAnnee() ?? ''); ?>" 
                               min="1900" max="<?php echo date('Y') + 10; ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="duree">Durée (minutes)</label>
                        <input type="number" id="duree" name="duree" 
                               value="<?php echo htmlspecialchars($film->getDuree()); ?>" 
                               min="1">
                    </div>
                </div>

                <div class="form-group">
                    <label for="bandeannonce">URL Bande-annonce</label>
                    <input type="url" id="bandeannonce" name="bandeannonce" 
                           value="<?php echo htmlspecialchars($film->getBandeAnnonce() ?? ''); ?>"
                           placeholder="https://...">
                </div>

                <div class="form-group">
                    <label for="datediffusion">Date de diffusion</label>
                    <input type="date" id="datediffusion" name="datediffusion" 
                           value="<?php echo htmlspecialchars($film->getDateDiffusion() ?? ''); ?>">
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-confirm">
                        <?php echo $film->getIdFilm() ? 'Modifier' : 'Ajouter'; ?> le film
                    </button>
                    <a href="<?php echo ROOT_PATH; ?>index.php?action=admin_list_films" class="btn-cancel">
                        Annuler
                    </a>
                </div>
            </form>
        </section>
    </main>
</div>

<?php require ROOT_DIR . 'views/layout/footer.php'; ?>

