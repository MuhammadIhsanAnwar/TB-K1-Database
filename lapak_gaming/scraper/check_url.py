import urllib.request
from bs4 import BeautifulSoup
import json

url = "https://www.itemku.com/g/mobile-legends/top-up"
req = urllib.request.Request(url, headers={'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Safari/537.36'})
try:
    with urllib.request.urlopen(req) as response:
        html = response.read().decode('utf-8')
        soup = BeautifulSoup(html, "html.parser")
        next_data = soup.find("script", {"id": "__NEXT_DATA__"})
        if next_data:
            data = json.loads(next_data.string)
            products = data.get('props', {}).get('pageProps', {}).get('products', [])
            productSSR = data.get('props', {}).get('pageProps', {}).get('productSSR', [])
            
            print(f"Products length: {len(products)}")
            if len(products) > 0:
                print("First product sample:")
                print(json.dumps(products[0], indent=2))
            
            print(f"ProductSSR length: {len(productSSR)}")
            if len(productSSR) > 0:
                print("First productSSR sample:")
                print(json.dumps(productSSR[0], indent=2))
except Exception as e:
    print("Error:", e)
