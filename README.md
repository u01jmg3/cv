## Setup

1. Ensure the latest version of [Composer](https://getcomposer.org/download/) is installed
1. Proceed with running the following command from a command prompt in the base of the repo folder

    ```sh
    $ composer update
    ```

1. To generate the PDF view `make.php` in a browser running your local PHP server
  - e.g. `http://localhost/cv/`

--

## How do you change the primary colour?

- Open `./html/cv.yaml` and change the `primaryColour` to another RGB colour


## How do you change the contents of the PDF?

- Open `./html/cv.yaml` and start typing

--

## How do you change the number of columns displayed in the Expertise section?

- Open `./html/includes/config.inc.php` and adjust the `COLUMNS_EXPERTISE` constant


## How do you change the number of rows displayed in the Professional Development section?

- Open `./html/includes/config.inc.php` and adjust the `ROWS_PROFESSIONAL_DEVELOPMENT` constant

## How do you display a profile picture?

- Open `./html/includes/config.inc.php` and change `SHOW_PROFILE_PICTURE` to `true`
  - Remember to populate `basics->picture` in `cv.yaml` with a link to an image


## How do you switch from generating a PDF to viewing as a web page?

- Open `./html/includes/config.inc.php` and change `PDF_MODE` to `false`

--

<kbd>
![CV](images/cv.png)
</kbd>

--

## License

- [CC BY-NC-ND 3.0](https://creativecommons.org/licenses/by-nc-nd/3.0/)