import email
import email.policy
import base64
from pathlib import Path


def convert(mhtml_path: Path, out_path: Path) -> int:
    raw = mhtml_path.read_bytes()
    msg = email.message_from_bytes(raw, policy=email.policy.default)

    if not msg.is_multipart():
        raise ValueError(f"{mhtml_path} is not a multipart mhtml file")

    html_part = None
    resources = {}  # Content-Location -> (mime, bytes)

    for part in msg.iter_parts():
        loc = part.get("Content-Location")
        ctype = part.get_content_type()
        payload = part.get_payload(decode=True)
        if payload is None:
            continue
        if html_part is None and ctype == "text/html":
            html_part = payload
            continue
        if loc:
            resources[loc] = (ctype, payload)

    if html_part is None:
        raise ValueError(f"No text/html part found in {mhtml_path}")

    html = html_part.decode("utf-8", errors="replace")

    for loc in sorted(resources.keys(), key=len, reverse=True):
        ctype, payload = resources[loc]
        b64 = base64.b64encode(payload).decode("ascii")
        html = html.replace(loc, f"data:{ctype};base64,{b64}")

    out_path.write_text(html, encoding="utf-8")
    return len(resources)


MAPPING = {
    "Urban Collective Action Network for India's cities _ U-CAN.mhtml": "u-can-homepage.html",
    "About U-CAN _ Urban Collective Action Network.mhtml": "u-can-about-us.html",
    "Our People _ U-CAN, Urban Collective Action Network.mhtml": "u-can-our-people.html",
    "Our Impact _ U-CAN, Urban Collective Action Network.mhtml": "u-can-impact.html",
    "Siddharth Pandit  Chief Executive Officer _ U-CAN.mhtml": "u-can-profile-siddharth-pandit.html",
    "Learning Network for Urban Managers _ U-CAN.mhtml": "u-can-learning-network.html",
    "Newsletter — Urban Governance Updates _ U-CAN.mhtml": "u-can-newsletter.html",
    "The Urban Brief  June 2026 _ U-CAN_newsletter_detailed.mhtml": "u-can-newsletter-urban-brief-june-2026.html",
}

if __name__ == "__main__":
    src_dir = Path(__file__).resolve().parent.parent
    dst_dir = src_dir / "pages"
    dst_dir.mkdir(parents=True, exist_ok=True)

    for src_name, out_name in MAPPING.items():
        src_path = src_dir / src_name
        if not src_path.exists():
            print(f"MISSING SOURCE: {src_name}")
            continue
        out_path = dst_dir / out_name
        n = convert(src_path, out_path)
        size_kb = out_path.stat().st_size / 1024
        print(f"OK  {out_name:50s}  {n:3d} resources inlined  {size_kb:8.1f} KB")
