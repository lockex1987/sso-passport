# Bước 1: Xóa các file, folder cũ và tạo mới
rm -rdf filtered
rm -f sso-passport-filtered.tar
mkdir filtered

# Bước 2: Chạy npm nếu cần
rm -rdf public/js/*
#npm run prod
#npm run dev

# Bước 3: Copy các thư mục
cp -r app filtered
cp -r config filtered

#cp -r public filtered
cd public
mkdir ../filtered/public
cp -r `ls -A | grep -v "storage"` ../filtered/public
cd ..

cp -r resources filtered
cp -r routes filtered

#cp -r bootstrap filtered
#cp -r database filtered
#cp -r storage filtered
#cp -r vendor filtered

#cp .env filtered
#cp artisan filtered
#cp server.php filtered

# Bước 4: Nén và đẩy file lên server
tar cvf sso-passport-filtered.tar filtered
#scp sso-passport-filtered.tar huyennv9@192.168.101.20:/home/huyennv9/filtered.tar
#ssh huyennv9@192.168.101.20 'bash -s' < remote_deploy.sh

# Bước 5: Có thể xóa luôn các file ở đây
rm -rdf filtered
#rm -f sso-passport-filtered.tar

echo "Finish"
