<?php
/**
 * DokuWiki Frontend(template) for Lore Archive // Pre-release privacy configuration
 *
 * @author   Cieron <cirrow@proton.me>
 * @license  GPL 2 (http://www.gnu.org/licenses/gpl.html)
 *
 */

if (!defined('DOKU_INC')) die(); 
@require_once(dirname(__FILE__).'/tpl_functions.php'); /* include hook for template functions. Unused in this code. I think */

$showTools = !tpl_getConf('hideTools') || ( tpl_getConf('hideTools') && !empty($_SERVER['REMOTE_USER']) ); /* PHP boolean variable to store information. */
$isAdmin = $INFO['isadmin']; /* Quite self-explanatory. Checks if current logged in user is an admin. */
$mainpageWidth = ''; /* This string variable contains the bootstrap class, more information can be found looking at its actual usage.  */
$tocWidth = 'col-xxl-3'; /* Just like $mainpageWidth. */
$namespacePeople = (strpos($ID, "people:") === 0) ? true : false; /* True if the current wiki page namespace begins with "people:". In other words, check if the current page is in the "PEOPLE" namespace. */
?>



<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $conf['lang'] ?>" lang="<?php echo $conf['lang'] ?>" dir="<?php echo $lang['direction'] ?>" class="no-js">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />

    <!--bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    

    <title><?php tpl_pagetitle() /* get the page title. */ ?> | <?php echo strip_tags($conf['title']) /* get the wiki title. */ ?></title>
    
    <script>(function(H){H.className=H.className.replace(/\bno-js\b/,'js')})(document.documentElement)</script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
    </script> <!-- Initializes bootstrap tooltips. -->
    


    <?php tpl_metaheaders() ?>
    <?php echo tpl_favicon(array('favicon', 'mobile')) /* favicon */ ?>

        
</head>



<body>

<?php if(!empty($_SERVER['REMOTE_USER'])): ?>

    <div id="dokuwiki__site" class="<?php echo tpl_classes() /* information about the wiki as CSS classes */ ?>">

        <?php require('surrounding/header/header.php') /* get the header */ ?>

        <div id="dokuwiki__nonheader">
            <div class="msgarea">
                <?php html_msgarea() /* occasional error and info messages on the page */ ?>
            </div>

            <div class="wikipage container-fluid"><div class="row">

                <nav id="dokuwiki__aside" aria-label="<?php echo $lang['sidebar'] ?>" class="col-auto d-none d-lg-block"> 
                    <div class="pad aside include group">
                        <?php  tpl_include_page($conf['sidebar'], 1, 1)  ?> 
                        <div class="clearer"></div>
                    </div>
                </nav>

                <main id="dokuwiki__content" class="col container-fluid"><div class="row">
                    <?php tpl_flush() /* flush the output buffer */ ?>

                    <?php
                        if (in_array($ID, ["home", "Home"])) {
                            $mainpageWidth = 'col-xxl-12';
                        } elseif ($namespacePeople) {
                            $mainpageWidth = 'col-xxl-10';
                        } else {
                            $mainpageWidth = 'col-xxl-9';
                        }
                        
                        if (!is_array($ACT) && in_array($ACT, ["edit", "search", "media"])) {
                            $mainpageWidth = 'col-xxl-12';
                        }
                        
                        
                        echo '<div id="dokuwiki__page" class="' . $mainpageWidth . ' col-xl-12 page">';
                    ?>

                        <?php if($INFO['isadmin']): ?>
                            <div id="lorearchive__pagetools">
                                <?php echo (new \dokuwiki\Menu\PageMenu())->getListItems(); ?>
                            </div>
                        <?php endif ?>

                        <?php if($conf['breadcrumbs']){ ?>
                            <div class="breadcrumbs"><?php tpl_breadcrumbs() ?></div>
                        <?php } ?>
                        

                        <?php if ($conf['useacl'] && $showTools): ?>


                        <?php endif ?>

                        <article data-bs-spy="scroll" data-bs-target="#dw__toc" data-bs-smooth-scroll="true" tabindex="0" class="wikicontent">

                            <!-- wikipage start -->
                            <?php tpl_content(false) /* the main content */ ?>
                            <!-- wikipage stop -->

                            <div class="footertext">
                                <p><em>Every article is a WIP and anything written may not be accurate or up to date.<br>All images used in this wiki belong to their respective owners</em>.</p>
                            </div>
                        
                        </article>
                        
                        <div class="clearer"></div>
                    </div>

                    <?php
                        if ($ID == "home" || $mainpageWidth == 'col-xxl-12') {
                            $tocWidth = 'col-xxl-0';

                        } elseif ($namespacePeople) {
                            $tocWidth = 'col-xxl-2';

                        }

                        if ($tocWidth !== 'col-xxl-0') {
                            echo '<div class="' . $tocWidth . ' d-none d-xxl-block toc">';
                            tpl_toc();
                            echo '</div>';
                        }
                    ?>

                    

                </div></main>
            </div></div>
        </div>

    </div>



    <!-- Search Modal -->
    <div class="modal fade" id="dokuwiki__searchModal" tabindex="-1" aria-labelledby="dokuwiki__searchModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="searchModalLabel">Search for pages...</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                <form id="dw__search"action="<?php echo wl(); ?>" method="get" class="d-flex search" role="search">
                    <input type="hidden" name="do" value="search">
                    <input type="hidden" name="id" value="<?php global $ID; echo $ID; ?>">

                    <input type="text" name="q" id="qsearch__in" class="form-control me-2 edit"  placeholder="Search" aria-label="Search" autocomplete="<?php echo $autocomplete ? 'on' : 'off'; ?>">
                </form>
                </div>
                
            </div>
        </div>
    </div>


    <!-- Sidebar Offcanvas -->
    <div class="offcanvas offcanvas-start" tabindex="-1" id="sidebarOffcanvas" aria-labelledby="sidebarOffcanvasLabel" style="min-width: 65%;">
        <div class="offcanvas-header">
            <?php tpl_link(wl(),'<img src="'.ml('logo.png').'" alt="'.$conf['title'].'" />') /* display wiki logo, RQ */ ?>
        </div>

        <hr>
        <div class="offcanvas-body">
            <div class="offcanvasHeaderlinks d-flex" style="justify-content: space-between;">
                <a href="<?php echo wl('changelog') ?>">CHANGELOG</a> <!--wl('foo') generates a wikilink which points to the page with id "foo" -->
                <a href="<?php echo wl('our_team') ?>">ABOUT</a>
                <a href="<?php echo wl('prealpha') ?>">PRE-ALPHA</a>  
            </div>

            <hr>
            <div class="pad aside include group">
                <?php tpl_include_page($conf['sidebar'], 1, 1) ?>


                <div class="clearer"></div>
            </div>
            <div class="clearer"></div>
        </div>
    </div>


<?php else: ?>

    <?php require('surrounding/header/header.php') ?>

    <div style="margin-top: 10vh;">
        <?php _tpl_usertools() ?>
        <?php tpl_content(false) ?>
    </div>

<?php endif ?>







    <div class="no"><?php tpl_indexerWebBug() /* provide DokuWiki housekeeping, required in all templates */ ?></div>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script>
        var scrollSpy = new bootstrap.ScrollSpy(document.body, {
            target: '#dw__toc',
            smoothScroll: true
        });
    </script>






</body>
</html>