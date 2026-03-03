import { useState, useEffect } from 'react';
import Layout from '../components/Layout';
import Link from 'next/link';
import { FiSearch, FiTrendingUp, FiStar, FiShoppingBag } from 'react-icons/fi';
import { productService } from '../lib/services';
import { useDispatch } from 'react-redux';
import { addToCart } from '../store/slices/cartSlice';
import { toast } from 'react-toastify';

export default function Home() {
  const [products, setProducts] = useState([]);
  const [categories, setCategories] = useState([]);
  const [searchQuery, setSearchQuery] = useState('');
  const [loading, setLoading] = useState(true);
  const dispatch = useDispatch();

  useEffect(() => {
    fetchData();
  }, []);

  const fetchData = async () => {
    try {
      const [productsRes, categoriesRes] = await Promise.all([
        productService.getProducts({ limit: 8, sort: 'popular' }),
        productService.getCategories()
      ]);

      if (productsRes.success) {
        setProducts(productsRes.data.products);
      }

      if (categoriesRes.success) {
        setCategories(categoriesRes.data);
      }
    } catch (error) {
      console.error('Error fetching data:', error);
    } finally {
      setLoading(false);
    }
  };

  const handleSearch = (e) => {
    e.preventDefault();
    if (searchQuery.trim()) {
      window.location.href = `/products?search=${encodeURIComponent(searchQuery)}`;
    }
  };

  const handleAddToCart = (product) => {
    dispatch(addToCart({ product, quantity: 1 }));
    toast.success(`${product.title} added to cart!`);
  };

  return (
    <Layout>
      {/* Hero Section */}
      <section className="relative bg-gradient-primary py-20 md:py-32">
        <div className="container mx-auto px-4">
          <div className="max-w-3xl mx-auto text-center text-white">
            <h1 className="text-4xl md:text-6xl font-bold mb-6 animate-fade-in">
              Welcome to Lapak Gaming
            </h1>
            <p className="text-xl md:text-2xl mb-8 opacity-90">
              Your trusted digital marketplace for game items, vouchers, and more!
            </p>

            {/* Search Bar */}
            <form onSubmit={handleSearch} className="max-w-2xl mx-auto">
              <div className="relative">
                <FiSearch className="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400 w-6 h-6" />
                <input
                  type="text"
                  placeholder="Search products..."
                  value={searchQuery}
                  onChange={(e) => setSearchQuery(e.target.value)}
                  className="w-full pl-14 pr-4 py-4 rounded-full text-gray-900 text-lg focus:outline-none focus:ring-4 focus:ring-white/30"
                />
                <button
                  type="submit"
                  className="absolute right-2 top-1/2 transform -translate-y-1/2 bg-secondary-600 text-white px-8 py-2 rounded-full hover:bg-secondary-700 transition"
                >
                  Search
                </button>
              </div>
            </form>
          </div>
        </div>
      </section>

      {/* Categories */}
      <section className="py-16 bg-white dark:bg-gray-800">
        <div className="container mx-auto px-4">
          <h2 className="text-3xl font-bold mb-8 text-center dark:text-white">
            Browse Categories
          </h2>
          <div className="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
            {categories.slice(0, 5).map((category) => (
              <Link
                key={category.id}
                href={`/products?category=${category.id}`}
                className="group p-6 bg-gray-50 dark:bg-gray-700 rounded-xl hover:shadow-lg transition text-center"
              >
                <div className="text-4xl mb-3">{category.icon || '📦'}</div>
                <h3 className="font-semibold dark:text-white group-hover:text-primary-600">
                  {category.name}
                </h3>
                <p className="text-sm text-gray-500 dark:text-gray-400 mt-1">
                  {category.product_count} products
                </p>
              </Link>
            ))}
          </div>
        </div>
      </section>

      {/* Trending Products */}
      <section className="py-16 bg-gray-50 dark:bg-gray-900">
        <div className="container mx-auto px-4">
          <div className="flex items-center justify-between mb-8">
            <h2 className="text-3xl font-bold dark:text-white flex items-center gap-2">
              <FiTrendingUp className="text-primary-600" />
              Trending Products
            </h2>
            <Link href="/products" className="text-primary-600 hover:text-primary-700 font-medium">
              View All →
            </Link>
          </div>

          {loading ? (
            <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
              {[...Array(8)].map((_, i) => (
                <div key={i} className="bg-white dark:bg-gray-800 rounded-xl overflow-hidden">
                  <div className="skeleton h-48 w-full"></div>
                  <div className="p-4">
                    <div className="skeleton h-4 w-3/4 mb-2"></div>
                    <div className="skeleton h-4 w-1/2"></div>
                  </div>
                </div>
              ))}
            </div>
          ) : (
            <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
              {products.map((product) => (
                <div
                  key={product.id}
                  className="bg-white dark:bg-gray-800 rounded-xl overflow-hidden shadow-md hover:shadow-xl transition group"
                >
                  <Link href={`/products/${product.id}`}>
                    <div className="relative h-48 bg-gray-200 dark:bg-gray-700 overflow-hidden">
                      {product.images && JSON.parse(product.images)[0] ? (
                        <img
                          src={JSON.parse(product.images)[0]}
                          alt={product.title}
                          className="w-full h-full object-cover group-hover:scale-110 transition"
                        />
                      ) : (
                        <div className="flex items-center justify-center h-full text-6xl">
                          🎮
                        </div>
                      )}
                      {product.featured && (
                        <span className="absolute top-2 left-2 bg-yellow-400 text-yellow-900 px-2 py-1 rounded text-xs font-bold">
                          Featured
                        </span>
                      )}
                    </div>
                  </Link>

                  <div className="p-4">
                    <Link href={`/products/${product.id}`}>
                      <h3 className="font-semibold text-lg mb-2 dark:text-white line-clamp-2 hover:text-primary-600">
                        {product.title}
                      </h3>
                    </Link>

                    <div className="flex items-center gap-1 mb-2 text-sm">
                      <FiStar className="text-yellow-400 fill-current" />
                      <span className="font-medium">{product.avg_rating ? parseFloat(product.avg_rating).toFixed(1) : '0.0'}</span>
                      <span className="text-gray-500">({product.review_count || 0})</span>
                    </div>

                    <div className="flex items-center justify-between">
                      <div>
                        <p className="text-2xl font-bold text-primary-600">
                          Rp {parseInt(product.price).toLocaleString('id-ID')}
                        </p>
                        <p className="text-xs text-gray-500">Stock: {product.stock}</p>
                      </div>
                      <button
                        onClick={() => handleAddToCart(product)}
                        disabled={product.stock === 0}
                        className="bg-primary-600 text-white p-3 rounded-lg hover:bg-primary-700 disabled:bg-gray-400 disabled:cursor-not-allowed transition"
                      >
                        <FiShoppingBag className="w-5 h-5" />
                      </button>
                    </div>
                  </div>
                </div>
              ))}
            </div>
          )}
        </div>
      </section>

      {/* Features */}
      <section className="py-16 bg-white dark:bg-gray-800">
        <div className="container mx-auto px-4">
          <h2 className="text-3xl font-bold mb-12 text-center dark:text-white">
            Why Choose Us?
          </h2>
          <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div className="text-center">
              <div className="text-5xl mb-4">🔒</div>
              <h3 className="text-xl font-semibold mb-2 dark:text-white">Secure Transactions</h3>
              <p className="text-gray-600 dark:text-gray-400">
                Escrow system protects both buyers and sellers
              </p>
            </div>
            <div className="text-center">
              <div className="text-5xl mb-4">⚡</div>
              <h3 className="text-xl font-semibold mb-2 dark:text-white">Instant Delivery</h3>
              <p className="text-gray-600 dark:text-gray-400">
                Automated delivery system for digital products
              </p>
            </div>
            <div className="text-center">
              <div className="text-5xl mb-4">💬</div>
              <h3 className="text-xl font-semibold mb-2 dark:text-white">24/7 Support</h3>
              <p className="text-gray-600 dark:text-gray-400">
                Real-time chat with sellers and support team
              </p>
            </div>
          </div>
        </div>
      </section>
    </Layout>
  );
}
