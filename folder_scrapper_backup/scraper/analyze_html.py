from bs4 import BeautifulSoup

with open("debug/debug.html", "r", encoding="utf-8") as f:
    html = f.read()

soup = BeautifulSoup(html, "html.parser")
print("Extracting all links:")

links = soup.find_all('a')
seen = set()
for a in links:
    href = a.get('href')
    if href and href not in seen:
        print(href)
        seen.add(href)
