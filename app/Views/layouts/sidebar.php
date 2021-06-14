<div class="page-sidebar-wrapper page-sidebar-fixed">
    <!-- BEGIN SIDEBAR -->
    <!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
    <!-- DOC: Change data-auto-speed="200" to adjust the sub menu slide up/down speed -->
    <div class="page-sidebar navbar-collapse collapse ">
        <!-- BEGIN SIDEBAR MENU -->
        <!-- DOC: Apply "page-sidebar-menu-light" class right after "page-sidebar-menu" to enable light sidebar menu style(without borders) -->
        <!-- DOC: Apply "page-sidebar-menu-hover-submenu" class right after "page-sidebar-menu" to enable hoverable(hover vs accordion) sub menu mode -->
        <!-- DOC: Apply "page-sidebar-menu-closed" class right after "page-sidebar-menu" to collapse("page-sidebar-closed" class must be applied to the body element) the sidebar sub menu mode -->
        <!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
        <!-- DOC: Set data-keep-expand="true" to keep the submenues expanded -->
        <!-- DOC: Set data-auto-speed="200" to adjust the sub menu slide up/down speed -->
        <ul class="page-sidebar-menu" data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200">

            <?php foreach (session()->get('user_login_vgdc')['menu'] as $key => $a) : ?>
                <?php foreach ($a as $key => $c) : ?>
                    <li class="nav-item start <?= uri_string() == '/' . $c['link'] ? 'active' : '' ?>">
                        <a href="<?= site_url($c['link']) ?>" class="nav-link">
                            <center>
                                <img src="<?= base_url('assets/' . $c['icon']) ?>" alt="placeholder+image" style="width: 30px;height:30px;margin-top: 14px;margin-bottom: 10px;"> <br>
                                <span class="title"><?= $c['nama'] ?></span>
                            </center>
                        </a>
                    </li>
                <?php endforeach ?>
            <?php endforeach; ?>

        </ul>
    </div>
    <!-- END SIDEBAR -->
</div>