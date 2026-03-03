import '../styles/globals.css';
import { Provider } from 'react-redux';
import { store } from '../store/store';
import { ToastContainer } from 'react-toastify';
import 'react-toastify/dist/ReactToastify.css';
import { useEffect } from 'react';
import { useRouter } from 'next/router';
import { authService } from '../lib/services';
import { setUser, logout } from '../store/slices/authSlice';
import { useDispatch } from 'react-redux';

function AuthInitializer() {
  const dispatch = useDispatch();
  const router = useRouter();

  useEffect(() => {
    const initAuth = async () => {
      try {
        const response = await authService.getCurrentUser();
        if (response.success) {
          dispatch(setUser(response.data));
        }
      } catch (error) {
        dispatch(logout());
      }
    };

    initAuth();
  }, [dispatch]);

  return null;
}

export default function App({ Component, pageProps }) {
  useEffect(() => {
    // Initialize theme
    const savedTheme = localStorage.getItem('theme');
    if (savedTheme === 'dark') {
      document.documentElement.classList.add('dark');
    }
  }, []);

  return (
    <Provider store={store}>
      <AuthInitializer />
      <Component {...pageProps} />
      <ToastContainer
        position="top-right"
        autoClose={3000}
        hideProgressBar={false}
        newestOnTop
        closeOnClick
        rtl={false}
        pauseOnFocusLoss
        draggable
        pauseOnHover
        theme="colored"
      />
    </Provider>
  );
}
