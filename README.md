# CU-Marks-Scrapper
Scrape marks from CU website.

Created By [@ABGEO07](https://github.com/ABGEO07) 4 [@glontianano](https://github.com/glontianano) :)

---

# ინსტალაცია

პროექტის დასაინსტალირებლად მიყევით შემდეგ ნაბიჯებს:

1. `git clone https://github.com/ABGEO07/CU-Marks-Scrapper.git`
1. `cd CU-Marks-Scrapper`
1. `composer install`

# კონფიგურაცია

პროექტის კონფიგურაცია ინახება `.env` ფაილში. დაარედაქტირეთ ის მითითების შესაბამისად:

- `MARKS_PATH` პარამეტრად მიუთითეთ სასურველი მისამართი საქაღალდემდე, სადაც გსურთ, რომ შეინახოს გასკრაპული ფაილები.
- `SCRAPPER_USERNAME`-ში მიუთითეთ თქვენი CU-ს მომხმარებლის სახელი, ხოლო `SCRAPPER_PASSWORD`-ში - პაროლი

# გამოყენება

სასურველი ცხრილის ნიშნების გასაგებად, მიყევით ინსტრუქციას:

- პირველ რიგში გაიგეთ სასურველი ცხრილის ID (`cxr_id`).
ამისთვის დალოგინდით საიტზე და გადადით https://programs.cu.edu.ge/students/masalebi_1.php გვერდზე.
გადადით გვერდის Source Code-ს დათვალიერების რეჟიმში (`ctrl + u`) და მოძებნეთ კოდის ფრაგმენტი,
სადაც აღწერილია საგნების სცრილის სტრუქტურა. ცხრილში თითოეული საგნის ქვევით არის დამალული ველი - `cxr_id`:

    ```html
    <input name="cxr_id" type="hidden"  value="253200" >
    ```
    დააკოპირეთ ველის მნიშვნელობა (ჩვენს შემთხვევაში `253200`);
- შემდეგ გაუშვით სკრაპერი, რომელსაც არგუმენტად გადასცემთ ცხრილის ID-ს:
`php bin/console scrape:schedule:marks 253200`. სკრიპტის მუშაობის დასრულების შემდეგ
თქვენს მიერ არჩეულ საქღალდეში გაჩნდება ახალი html ფაილი (მაგ.: `schedule_253200.html`),
რომელიც შეიცავს არჩეული ცხრილის ნიშნების გვერდს.
