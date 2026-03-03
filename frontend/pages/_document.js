import { Html, Head, Main, NextScript } from 'next/document';

export default function Document() {
  return (
    <Html lang="en">
      <Head>
        <meta charSet="utf-8" />
        <link rel="icon" href="/favicon.ico" />
        <meta name="description" content="Lapak Gaming - Digital Marketplace for Game Items, Vouchers, Accounts & Top-Up Services" />
        <meta name="keywords" content="gaming, marketplace, digital, voucher, game items, top-up" />
        <meta name="theme-color" content="#8b5cf6" />
      </Head>
      <body>
        <Main />
        <NextScript />
      </body>
    </Html>
  );
}
