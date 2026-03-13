
path = r"c:\xamppp\htdocs\Kaya Tene\css\style.css"
with open(path, 'r', encoding='utf-8') as f:
    content = f.read()

open_count = content.count('{')
close_count = content.count('}')

print(f"Open: {open_count}, Close: {close_count}")

# Check for unclosed media queries or blocks
stack = []
for i, char in enumerate(content):
    if char == '{':
        stack.append(i)
    elif char == '}':
        if not stack:
            print(f"Extra closing brace at position {i}")
        else:
            stack.pop()

if stack:
    print(f"Unclosed braces at positions: {stack}")
else:
    print("All braces matched.")
