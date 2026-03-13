<?php
require_once '../config/Connection.php';
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struktur Organisasi - Yayasan Kaya Tene</title>
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>
    <?php require_once '../partials/Loader.php'; ?>
    <?php include '../partials/Navbar.php'; ?>

    <section class="section" id="struktur-organisasi" style="padding-top: 150px;">
        <div class="container">
            <h2 class="section-title">Struktur <span class="text-gradient">Organisasi</span></h2>
            
            <div class="org-tree-container" style="margin-top: 50px;">
                <?php
                $sql_org = "SELECT nama, jabatan, gambar FROM Struktur_Organisasi ORDER BY id ASC";
                $result_org = $conn->query($sql_org);
                
                $orgs = [];
                if ($result_org && $result_org->num_rows > 0) {
                    while ($org = $result_org->fetch_assoc()) {
                        $orgs[] = $org;
                    }
                }

                if (!empty($orgs)) {
                    function renderOrgCard($org) {
                        $img_org = !empty($org['gambar']) ? '../uploads/' . htmlspecialchars($org['gambar']) : 'https://images.unsplash.com/photo-1542601906990-b4d3fb778b09?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80';
                        return '
                        <div class="org-node">
                            <div class="team-card glass" style="border-radius: 20px; overflow: hidden; text-align: center; padding-bottom: 20px; display: flex; flex-direction: column; width: 240px; margin: 0 auto; border-bottom: 3px solid var(--primary);">
                                <img src="' . $img_org . '" alt="' . htmlspecialchars($org['nama']) . '" style="width: 100%; height: 260px; object-fit: cover;" onerror="this.src=\'https://images.unsplash.com/photo-1542601906990-b4d3fb778b09?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80\'">
                                <div style="padding: 20px 15px 5px; flex-grow: 1; display: flex; flex-direction: column; justify-content: center;">
                                    <h4 style="font-size: 1.15rem; font-weight: 700; color: var(--text-main); margin-bottom: 8px;">' . htmlspecialchars($org['nama']) . '</h4>
                                    <p style="color: var(--primary); font-size: 0.85rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 0;">' . htmlspecialchars($org['jabatan']) . '</p>
                                </div>
                            </div>
                        </div>';
                    }

                    echo '<div class="org-tree"><ul><li>';
                    
                    // Root Node
                    echo renderOrgCard($orgs[0]);
                    
                    // Children Nodes
                    if (count($orgs) > 1) {
                        echo '<ul>';
                        
                        // Determine Level 2 and Level 3
                        $total = count($orgs);
                        $level2_count = min(2, $total - 1); 
                        
                        for ($i = 1; $i <= $level2_count; $i++) {
                            echo '<li>';
                            echo renderOrgCard($orgs[$i]);
                            
                            // Distribute remaining nodes to Level 3 evenly
                            $level3 = [];
                            for ($j = $level2_count + 1; $j < $total; $j++) {
                                if (($j - $level2_count - 1) % $level2_count == ($i - 1)) {
                                    $level3[] = $orgs[$j];
                                }
                            }
                            
                            if (!empty($level3)) {
                                echo '<ul>';
                                foreach ($level3 as $child) {
                                    echo '<li>' . renderOrgCard($child) . '</li>';
                                }
                                echo '</ul>';
                            }
                            echo '</li>';
                        }
                        
                        echo '</ul>';
                    }
                    
                    echo '</li></ul></div>';
                } else {
                    echo '<div class="glass" style="grid-column: 1/-1; padding: 40px; text-align: center; border-radius: 20px; color: var(--text-muted); font-size: 1.1rem; border: 1px dashed var(--glass-border);">Struktur organisasi belum tersedia.</div>';
                }
                ?>
            </div>
            
            <style>
                .org-tree-container {
                    width: 100%;
                    overflow-x: auto; 
                    padding-bottom: 20px;
                }

                .org-tree {
                    display: flex;
                    justify-content: center;
                }

                .org-tree ul {
                    padding-top: 20px;
                    position: relative;
                    transition: all 0.5s;
                    display: flex;
                    justify-content: center;
                    list-style-type: none;
                    padding-left: 0;
                    margin: 0;
                }

                .org-tree li {
                    text-align: center;
                    list-style-type: none;
                    position: relative;
                    padding: 20px 15px 0 15px;
                    transition: all 0.5s;
                }

                .org-tree li::before, .org-tree li::after{
                    content: '';
                    position: absolute;
                    top: 0;
                    right: 50%;
                    border-top: 2px solid var(--primary);
                    width: 50%;
                    height: 20px;
                }
                .org-tree li::after{
                    right: auto;
                    left: 50%;
                    border-left: 2px solid var(--primary);
                }

                .org-tree li:only-child::after, .org-tree li:only-child::before {
                    display: none;
                }
                .org-tree li:only-child{ padding-top: 0;}

                .org-tree li:first-child::before, .org-tree li:last-child::after{
                    border: 0 none;
                }
                
                .org-tree li:last-child::before{
                    border-right: 2px solid var(--primary);
                    border-radius: 0 5px 0 0;
                }
                .org-tree li:first-child::after{
                    border-radius: 5px 0 0 0;
                }

                .org-tree ul ul::before{
                    content: '';
                    position: absolute;
                    top: 0;
                    left: 50%;
                    border-left: 2px solid var(--primary);
                    width: 0;
                    height: 20px;
                }

                .org-node {
                    display: inline-block;
                }

                .team-card {
                    transition: transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275), box-shadow 0.4s ease, border-color 0.4s ease;
                }
                .team-card:hover {
                    transform: translateY(-10px);
                    box-shadow: 0 15px 30px rgba(0, 0, 0, 0.5);
                    border-bottom-color: var(--secondary) !important; 
                }
                body.light-mode .team-card:hover {
                    box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
                }

                .org-tree-container::-webkit-scrollbar {
                    height: 8px;
                }
                .org-tree-container::-webkit-scrollbar-track {
                    background: rgba(0,0,0,0.1);
                    border-radius: 10px;
                }
                .org-tree-container::-webkit-scrollbar-thumb {
                    background: var(--primary);
                    border-radius: 10px;
                }
            </style>
        </div>
    </section>

    <?php include '../partials/Footer.php'; ?>
</body>

</html>
