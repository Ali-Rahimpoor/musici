<?php require "./parts/header.php";
/**
 * @var string $user_role
 */

is_user_admin($user_role);
    $res = db_query("SELECT * FROM categories");
    $categories='';
    if($res && $res->num_rows){
        $categories = mysqli_fetch_all($res,MYSQLI_ASSOC);
    }
?>
        <!-- سایدبار -->
        <?php require "./parts/sidebar.php" ?>    
        
            <div class="page-content active" id="add-category-page">
                <div class="form-container">
                    <h2>اضافه کردن دسته‌بندی جدید</h2>
                    <form id="addCategoryForm">
                        <div class="form-group">
                            <label>نام دسته‌بندی</label>
                            <input type="text" name='title' class="form-input" id="categoryName" placeholder="مثال: پاپ، رپ، کلاسیک">
                        </div>
                        <button type="submit" class="btn-primary">ایجاد دسته‌بندی</button>
                    </form>
                    
                    <div class="categories-list">
                        <h3>دسته‌بندی‌های موجود</h3>
                        <div id="existingCategories">
                            <?php if(!$categories): ?>
                                <div>دسته بندی یافت نشد</div>

                                <?php else: ?>
                                    <?php foreach($categories as $cat): ?>
                                        <div class="category-tag"><?php echo $cat['title']; ?><button data-id='<?php echo $cat['ID']; ?>' class="delete-cat">✖</button></div>  
                                    <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            
    <?php require "./parts/footer.php"; ?>