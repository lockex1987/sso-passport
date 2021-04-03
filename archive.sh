# Zip lại các thư mục, để chuyển giữa máy ở nhà và máy ở công ty

# Bước 1: Xóa các file, folder cũ và tạo mới
rm -rdf public/js/*
rm -rdf archive
rm -f sso-passport-archive.tar
mkdir archive

# Bước 2: Copy các thư mục, file
cp -r app archive
# cp -r bootstrap archive
cp -r config archive
# cp -r database archive
# cp -r node_modules archive

# cp -r public archive
cd public
mkdir ../archive/public
cp -r `ls -A | grep -v "storage"` ../archive/public
cd ..

cp -r resources archive
cp -r routes archive
# cp -r storage archive
# cp -r tests archive
# cp -r vendor archive

# cp .env archive
cp .eslintrc.json archive
cp .gitignore archive
cp archive.sh archive
# cp artisan archive
# cp composer.json archive
cp db.php archive
cp deploy.sh archive
cp package.json archive
cp README.md archive
# cp server.php archive
cp webpack.mix.js archive


# Bước 3: Nén file
# Có thể xóa luôn các file ở đây
tar cvf sso-passport-archive.tar archive
# rm -rdf archive

echo "Finish"
