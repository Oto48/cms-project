    <footer class="bg-dark text-white py-3 mt-auto">
        <div class="container text-center">
            <p class="mb-0">&copy; 2045 CMS PDO System. All rights reserved by Oto Tchokhonelidze</p>
        </div>
    </footer>

    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"

    ></script>

    <script>
        function confirmDelete(articleId) {
            if (confirm('Are you sure you want to delete this article?')) {
                alert('Article ' + articleId + ' deleted.');
            }
        }
    </script>
</body>
</html>