<template>
	<transition name="fade">
		<div class="page home-page">
			<home-preloader 
				v-if="defaultInit && !user.detail.onboarding_edit || defaultInit && !user.detail.onboarding_tariff"
				:isOpen="defaultInit"
			>
			</home-preloader>
			<Steps 
				v-else-if="!user.detail.onboarding_edit && !defaultInit || !user.detail.onboarding_tariff && !defaultInit || user.detail.password_reset && !defaultInit" 
				:key="keyValue"
			/>

			<div class="mainInner">	
				<div class="mainInner-column">

					<div class="mainInnerItems">

						<div class="mainInnerItems__item">
							<router-link tag="a" class="mainInnerItems__link" to="/products">
								<img src="@/assets/img/main_items/prods.svg" alt="" class="mainInnerItems__icon">
								<span class="mainInnerItems__name">Товары</span>
							</router-link>

							<ul class="mainInnerItems__childList">

								<router-link
									tag="li"
									v-for="val in menuList.products"
									:key="val.url"
									:to="val.url"
								>
									<a href="/">
										{{ val.title }}
									</a>
								</router-link>

							</ul>
						</div>

						<div class="mainInnerItems__item">
							<router-link tag="a" class="mainInnerItems__link" to="/directories">
								<img src="@/assets/img/main_items/directories.svg" alt="" class="mainInnerItems__icon">
								<span class="mainInnerItems__name">Справочники</span>
							</router-link>

							<ul class="mainInnerItems__childList">

								<router-link
									tag="li"
									v-for="val in menuList.directories"
									:key="val.url"
									:to="val.url"
								>
									<a href="/">
										{{ val.title }}
									</a>
								</router-link>

							</ul>
						</div>

						<div class="mainInnerItems__item">
							<router-link tag="a" class="mainInnerItems__link" to="/system_management">
								<img src="@/assets/img/main_items/settings.svg" alt="" class="mainInnerItems__icon">
								<span class="mainInnerItems__name">Управление системой</span>
							</router-link>

							<ul class="mainInnerItems__childList">

								<router-link
									tag="li"
									v-for="val in menuList.systemManagement"
									:key="val.url"
									:to="val.url"
								>
									<a href="/">
										{{ val.title }}
									</a>
								</router-link>

							</ul>
						</div>
						
					</div>

					<div class="mainInnerBanners">
						<!-- тут будут какие то баннеры -->
						<div class="mainInnerBanners__banner">
							<img src="" alt="">
						</div>
						<div class="mainInnerBanners__banner">
							<img src="" alt="">
						</div>
					</div>

				</div>
				<div class="mainInner-column">
					<div class="mainInnerSidebar myScroll">
						<div class="mainInnerSidebar__title">Как это работает:</div>
						<div class="mainInnerSidebarItems">

						<!-- --open на кнопке меняет на минус
							--open на списке открывает его -->
							<div v-for="(val, index) in accordionItems.data" :key="index" :data-item-id="val.id" class="mainInnerSidebarItems__item">
								<div class="mainInnerSidebarItems__name">{{ val.title }}</div>
								<button :class="{'mainInnerSidebarItems__btn': true, 'mainInnerSidebarItems__btn--open': false}" :ref="`button_${index}`" @click="toggle"></button>			
								<div :class="{'mainInnerSidebarItems__list': true, 'mainInnerSidebarItems__list--open': false}">
									<ul>
										<li v-for="(item, idx) in val.child" :key="idx" :data-itemlist-id="item.idx">
											<a href="#">{{ item.title }}</a>
										</li>
									</ul>
								</div>			
							</div>
						</div>
					</div>
				</div>
				
			</div>

			<!-- notifications and reminders -->


			<div class="notificationsWinWrap">
				<div class="notificationsWin">


					<div class="notificationsWinBtns">
						<button class="notificationsWinBtns__btn notificationsWinBtns__btn--active1 notificationsWinBtns__notificates">Уведомления</button>
						<button class="notificationsWinBtns__btn notificationsWinBtns__btn--active1 notificationsWinBtns__reminders">Напоминания</button>
					</div>


					<div class="notificationsWinNotificates notificationsWinNotificates--open myScroll myScroll--notificates">
						<div class="notificationsWinNotificatesDay">
							<div class="notificationsWinNotificatesDay__title">Сегодня, 19.10.2020</div>
							<div class="notificationsWinNotificatesDay__item">
								<div class="notificationsWinNotificatesDay__icon">
									<img src="@/assets/icons/icon_created.svg" alt="">
								</div>
								<div class="notificationsWinNotificatesDay__info">
									<div class="notificationsWinNotificatesDay__time">19.10.2020 <br> 16:00</div>
									<div class="notificationsWinNotificatesDay__name">
										Создан документ <span>“Закупка OS-022154562”</span>.
										<a class="notificationsWinNotificatesDay__link" href="">Открыть</a>
									</div>
								</div>
							</div>
							<div class="notificationsWinNotificatesDay__item">
								<div class="notificationsWinNotificatesDay__icon">
									<img src="@/assets/icons/icon_write.svg" alt="">
								</div>
								<div class="notificationsWinNotificatesDay__info">
									<div class="notificationsWinNotificatesDay__time">19.10.2020 <br> 16:00</div>
									<div class="notificationsWinNotificatesDay__name">
										Документ <span>“Закупка OS-022154562”</span> отредактирован.
										<a class="notificationsWinNotificatesDay__link" href="">Открыть</a>
									</div>
								</div>
							</div>
							<div class="notificationsWinNotificatesDay__item">
								<div class="notificationsWinNotificatesDay__icon">
									<img src="@/assets/icons/icon_deleted.svg" alt="">
								</div>
								<div class="notificationsWinNotificatesDay__info">
									<div class="notificationsWinNotificatesDay__time">19.10.2020 <br> 16:00</div>
									<div class="notificationsWinNotificatesDay__name">
										Документ <span>“Закупка OS-022154562”</span> удален.
										<a class="notificationsWinNotificatesDay__link" href="">Открыть</a>
									</div>
								</div>
							</div>
							<div class="notificationsWinNotificatesDay__item">
								<div class="notificationsWinNotificatesDay__icon">
									<img src="@/assets/icons/icon_prov.svg" alt="">
								</div>
								<div class="notificationsWinNotificatesDay__info">
									<div class="notificationsWinNotificatesDay__time">19.10.2020 <br> 16:00</div>
									<div class="notificationsWinNotificatesDay__name">
										Документ <span>“Закупка OS-022154562”</span> проведен.
										<a class="notificationsWinNotificatesDay__link" href="">Открыть</a>
									</div>
								</div>
							</div>
						</div>
						<div class="notificationsWinNotificatesDay">
							<div class="notificationsWinNotificatesDay__title">Вчера, 18.10.2020</div>
							<div class="notificationsWinNotificatesDay__item">
								<div class="notificationsWinNotificatesDay__icon">
									<img src="@/assets/icons/icon_doc_cancel.svg" alt="">
								</div>
								<div class="notificationsWinNotificatesDay__info">
									<div class="notificationsWinNotificatesDay__time">18.10.2020 <br> 16:00</div>
									<div class="notificationsWinNotificatesDay__name">
										Отмена проведения документа <span>“Закупка OS-022154562”</span>.
										<a class="notificationsWinNotificatesDay__link" href="">Открыть</a>
									</div>
								</div>
							</div>
							<div class="notificationsWinNotificatesDay__item">
								<div class="notificationsWinNotificatesDay__icon">
									<img src="@/assets/icons/icon_prod_add.svg" alt="">
								</div>
								<div class="notificationsWinNotificatesDay__info">
									<div class="notificationsWinNotificatesDay__time">18.10.2020 <br> 16:00</div>
									<div class="notificationsWinNotificatesDay__name">
										Добавлен товар <span>“Куртка OSTIN”</span>.
										<a class="notificationsWinNotificatesDay__link" href="">Открыть</a>
									</div>
								</div>
							</div>
							<div class="notificationsWinNotificatesDay__item">
								<div class="notificationsWinNotificatesDay__icon">
									<img src="@/assets/icons/icon_translate.svg" alt="">
								</div>
								<div class="notificationsWinNotificatesDay__info">
									<div class="notificationsWinNotificatesDay__time">18.10.2020 <br> 16:00</div>
									<div class="notificationsWinNotificatesDay__name">
										Товар <span>“Куртка OSTIN”</span> перемещен.
										<a class="notificationsWinNotificatesDay__link" href="">Открыть</a>
									</div>
								</div>
							</div>
							<div class="notificationsWinNotificatesDay__item">
								<div class="notificationsWinNotificatesDay__icon">
									<img src="@/assets/icons/icon_prod_deleted.svg" alt="">
								</div>
								<div class="notificationsWinNotificatesDay__info">
									<div class="notificationsWinNotificatesDay__time">18.10.2020 <br> 16:00</div>
									<div class="notificationsWinNotificatesDay__name">
										Товар <span>“Куртка OSTIN”</span> удален.
										<a class="notificationsWinNotificatesDay__link" href="">Открыть</a>
									</div>
								</div>
							</div>
						</div>
						<div class="notificationsWinNotificatesDay">
							<div class="notificationsWinNotificatesDay__title">Вчера, 18.10.2020</div>
							<div class="notificationsWinNotificatesDay__item">
								<div class="notificationsWinNotificatesDay__icon">
									<img src="@/assets/icons/icon_doc_cancel.svg" alt="">
								</div>
								<div class="notificationsWinNotificatesDay__info">
									<div class="notificationsWinNotificatesDay__time">18.10.2020 <br> 16:00</div>
									<div class="notificationsWinNotificatesDay__name">
										Отмена проведения документа <span>“Закупка OS-022154562”</span>.
										<a class="notificationsWinNotificatesDay__link" href="">Открыть</a>
									</div>
								</div>
							</div>
							<div class="notificationsWinNotificatesDay__item">
								<div class="notificationsWinNotificatesDay__icon">
									<img src="@/assets/icons/icon_prod_add.svg" alt="">
								</div>
								<div class="notificationsWinNotificatesDay__info">
									<div class="notificationsWinNotificatesDay__time">18.10.2020 <br> 16:00</div>
									<div class="notificationsWinNotificatesDay__name">
										Добавлен товар <span>“Куртка OSTIN”</span>.
										<a class="notificationsWinNotificatesDay__link" href="">Открыть</a>
									</div>
								</div>
							</div>
							<div class="notificationsWinNotificatesDay__item">
								<div class="notificationsWinNotificatesDay__icon">
									<img src="@/assets/icons/icon_translate.svg" alt="">
								</div>
								<div class="notificationsWinNotificatesDay__info">
									<div class="notificationsWinNotificatesDay__time">18.10.2020 <br> 16:00</div>
									<div class="notificationsWinNotificatesDay__name">
										Товар <span>“Куртка OSTIN”</span> перемещен.
										<a class="notificationsWinNotificatesDay__link" href="">Открыть</a>
									</div>
								</div>
							</div>
							<div class="notificationsWinNotificatesDay__item">
								<div class="notificationsWinNotificatesDay__icon">
									<img src="@/assets/icons/icon_prod_deleted.svg" alt="">
								</div>
								<div class="notificationsWinNotificatesDay__info">
									<div class="notificationsWinNotificatesDay__time">18.10.2020 <br> 16:00</div>
									<div class="notificationsWinNotificatesDay__name">
										Товар <span>“Куртка OSTIN”</span> удален.
										<a class="notificationsWinNotificatesDay__link" href="">Открыть</a>
									</div>
								</div>
							</div>
						</div>
					</div>


					<div class="notificationsWinReminders notificationsWinReminders--open1 myScroll myScroll--reminders">
						<div class="notificationsWinReminders__calendar">
							<!-- тут будет календарь -->
						</div>
						<div class="notificationsWinReminders__items">

							<div class="notificationsWinReminders__item">
								<div class="notificationsWinReminders__time">10:00</div>
								<button class="notificationsWinReminders__alarm notificationsWinReminders__alarm--enable">
									<img src="@/assets/icons/alarm_enabled.svg" alt="">
									<img src="@/assets/icons/alarm_disabled.svg" alt="">
								</button>
								<div class="notificationsWinReminders__name">Закупка OS-022154562</div>
								<div class="notificationsWinReminders__drag">
									<img src="@/assets/icons/drag.svg" alt="">
								</div>
								<div class="notificationsWinReminders__write">
									<input type="text" name="item_write" placeholder="Уточнить количество товара">
								</div>
							</div>

							<div class="notificationsWinReminders__item">
								<div class="notificationsWinReminders__time">11:00</div>
								<button class="notificationsWinReminders__alarm notificationsWinReminders__alarm--disable">
									<img src="@/assets/icons/alarm_enabled.svg" alt="">
									<img src="@/assets/icons/alarm_disabled.svg" alt="">								
								</button>
								<div class="notificationsWinReminders__name">Задача “Оприходовать товар”</div>
								<div class="notificationsWinReminders__drag">
									<img src="@/assets/icons/drag.svg" alt="">
								</div>
								<div class="notificationsWinReminders__write">
									<input type="text" name="item_write" placeholder="Закупка от 14.09.2020">
								</div>
							</div>

							<div class="notificationsWinReminders__item">
								<div class="notificationsWinReminders__time">12:00</div>
								<button class="notificationsWinReminders__alarm notificationsWinReminders__alarm--enable">
									<img src="@/assets/icons/alarm_enabled.svg" alt="">
									<img src="@/assets/icons/alarm_disabled.svg" alt="">
								</button>
								<div class="notificationsWinReminders__name">Закупка OS-022154562</div>
								<div class="notificationsWinReminders__drag">
									<img src="@/assets/icons/drag.svg" alt="">
								</div>
								<div class="notificationsWinReminders__write">
									<input type="text" name="item_write" placeholder="Уточнить количество товара">
								</div>
							</div>

							<div class="notificationsWinReminders__item">
								<div class="notificationsWinReminders__time">13:00</div>
								<button class="notificationsWinReminders__alarm notificationsWinReminders__alarm--enable">
									<img src="@/assets/icons/alarm_enabled.svg" alt="">
									<img src="@/assets/icons/alarm_disabled.svg" alt="">
								</button>
								<div class="notificationsWinReminders__name">Закупка OS-022154562</div>
								<div class="notificationsWinReminders__drag">
									<img src="@/assets/icons/drag.svg" alt="">
								</div>
								<div class="notificationsWinReminders__write">
									<input type="text" name="item_write" placeholder="Уточнить количество товара">
								</div>
							</div>

							<div class="notificationsWinReminders__item">
								<div class="notificationsWinReminders__time">14:00</div>
								<button class="notificationsWinReminders__alarm notificationsWinReminders__alarm--disable">
									<img src="@/assets/icons/alarm_enabled.svg" alt="">
									<img src="@/assets/icons/alarm_disabled.svg" alt="">
								</button>
								<div class="notificationsWinReminders__name">Задача “Оприходовать товар”</div>
								<div class="notificationsWinReminders__drag">
									<img src="@/assets/icons/drag.svg" alt="">
								</div>
								<div class="notificationsWinReminders__write">
									<input type="text" name="item_write" placeholder="Закупка от 14.09.2020">
								</div>
							</div>

							<div class="notificationsWinReminders__item">
								<div class="notificationsWinReminders__time">15:00</div>
								<button class="notificationsWinReminders__alarm notificationsWinReminders__alarm--disable">
									<img src="@/assets/icons/alarm_enabled.svg" alt="">
									<img src="@/assets/icons/alarm_disabled.svg" alt="">
								</button>
								<div class="notificationsWinReminders__name">Задача “Оприходовать товар”</div>
								<div class="notificationsWinReminders__drag">
									<img src="@/assets/icons/drag.svg" alt="">
								</div>
								<div class="notificationsWinReminders__write">
									<input type="text" name="item_write" placeholder="Закупка от 14.09.2020">
								</div>
							</div>

							<div class="notificationsWinReminders__item">
								<div class="notificationsWinReminders__time">15:00</div>
								<button class="notificationsWinReminders__alarm notificationsWinReminders__alarm--disable">
									<img src="@/assets/icons/alarm_enabled.svg" alt="">
									<img src="@/assets/icons/alarm_disabled.svg" alt="">
								</button>
								<div class="notificationsWinReminders__name">Задача “Оприходовать товар”</div>
								<div class="notificationsWinReminders__drag">
									<img src="@/assets/icons/drag.svg" alt="">
								</div>
								<div class="notificationsWinReminders__write">
									<input type="text" name="item_write" placeholder="Закупка от 14.09.2020">
								</div>
							</div>

							<div class="notificationsWinReminders__item">
								<div class="notificationsWinReminders__time">15:00</div>
								<button class="notificationsWinReminders__alarm notificationsWinReminders__alarm--disable">
									<img src="@/assets/icons/alarm_enabled.svg" alt="">
									<img src="@/assets/icons/alarm_disabled.svg" alt="">
								</button>
								<div class="notificationsWinReminders__name">Задача “Оприходовать товар”</div>
								<div class="notificationsWinReminders__drag">
									<img src="@/assets/icons/drag.svg" alt="">
								</div>
								<div class="notificationsWinReminders__write">
									<input type="text" name="item_write" placeholder="Закупка от 14.09.2020">
								</div>
							</div>

							<div class="notificationsWinReminders__item">
								<div class="notificationsWinReminders__time">15:00</div>
								<button class="notificationsWinReminders__alarm notificationsWinReminders__alarm--disable">
									<img src="@/assets/icons/alarm_enabled.svg" alt="">
									<img src="@/assets/icons/alarm_disabled.svg" alt="">
								</button>
								<div class="notificationsWinReminders__name">Задача “Оприходовать товар”</div>
								<div class="notificationsWinReminders__drag">
									<img src="@/assets/icons/drag.svg" alt="">
								</div>
								<div class="notificationsWinReminders__write">
									<input type="text" name="item_write" placeholder="Закупка от 14.09.2020">
								</div>
							</div>

						</div>
						<button class="buttonAdd buttonAdd--orange notificationsWinReminders__add"></button>
					</div>


				</div>
			</div>

			<!-- ui kit elements -->

			<div class="uikit">


				<div class="search">
					<!-- --onWrited заменяет иконку на крест и активирует функцию очистки поля -->
					<form action="" class="search__form search__form--onWrited1">
						<button type="button" class="search__filter">
							<img src="@/assets/icons/search_filter.svg" alt="">
						</button>
						<input type="text" placeholder="Поиск" name="search_text" class="search__inner">
						<input type="reset" value="" class="search__reset">
					</form>
				</div>


				<div class="rate">
					<form action="" class="rate__form">

						<div class="rate__item">						
							<input type="radio" id="rate__one" name="rate" value="1">
							<label class="rate__label rate__label--one" for="rate__one">1</label>
						</div>

						<div class="rate__item">						
							<input type="radio" id="rate__two" name="rate" value="2">
							<label class="rate__label rate__label--two" for="rate__two">2</label>
						</div>

						<div class="rate__item">						
							<input type="radio" id="rate__three" name="rate" value="3">
							<label class="rate__label rate__label--three" for="rate__three">3</label>
						</div>

						<div class="rate__item">						
							<input type="radio" id="rate__four" name="rate" value="4">
							<label class="rate__label rate__label--four" for="rate__four">4</label>
						</div>

						<div class="rate__item">						
							<input type="radio" id="rate__five" name="rate" value="5">
							<label class="rate__label rate__label--five" for="rate__five">5</label>
						</div>

						<div class="rate__item">						
							<input type="radio" id="rate__six" name="rate" value="6">
							<label class="rate__label rate__label--six" for="rate__six">6</label>
						</div>

						<div class="rate__item">						
							<input type="radio" id="rate__seven" name="rate" value="7">
							<label class="rate__label rate__label--seven" for="rate__seven">7</label>
						</div>

						<div class="rate__item">						
							<input type="radio" id="rate__eight" name="rate" value="8">
							<label class="rate__label rate__label--eight" for="rate__eight">8</label>
						</div>

						<div class="rate__item">						
							<input type="radio" id="rate__nine" name="rate" value="9">
							<label class="rate__label rate__label--nine" for="rate__nine">9</label>
						</div>

						<div class="rate__item">						
							<input type="radio" id="rate__ten" name="rate" value="10">
							<label class="rate__label rate__label--ten" for="rate__ten">10</label>
						</div>

					</form>
				</div>


				<div class="dropdown">
					<!-- --open открывает список -->
					<div class="dropdown__title dropdown__title--open1">
						<span>Все</span>
						<ul class="dropdown__list myScroll myScroll--dropdown">
							<li class="dropdown__item">
								<a href="#">Работа с предложением</a>
							</li>
							<li class="dropdown__item">
								<a href="#">Формирование интереса</a>
							</li>
							<li class="dropdown__item">
								<a href="#">Убеждение</a>
							</li>
							<li class="dropdown__item">
								<a href="#">Работа с предложением</a>
							</li>
							<li class="dropdown__item">
								<a href="#">Формирование интереса</a>
							</li>
							<li class="dropdown__item">
								<a href="#">Убеждение</a>
							</li>
						</ul>
					</div>
				</div>


				<div class="dropdown dropdown--add">
					<!-- --open открывает список -->
					<div class="dropdown__title dropdown__title--open1">
						<span>Добавить</span>
						<ul class="dropdown__list myScroll myScroll--dropdown">
							<li class="dropdown__item">
								<a href="#">Группу</a>
							</li>
							<li class="dropdown__item">
								<a href="#">Склад</a>
							</li>
						</ul>
					</div>
				</div>

				<div class="dropdown dropdown--import">
					<!-- --open открывает список -->
					<div class="dropdown__title dropdown__title--open1">
						<span>Импорт / экспорт</span>
						<ul class="dropdown__list myScroll myScroll--dropdown">
							<li class="dropdown__item">
								<a href="#">Импорт</a>
							</li>
							<li class="dropdown__item">
								<a href="#">Экспорт</a>
							</li>
						</ul>
					</div>
				</div>

			</div>
			
		</div>
	</transition>	
</template>

<script>
	
</script>

<script>
import Steps from '@/components/ui/Steps'
import { mapGetters, mapActions } from "vuex"
import HomePreloader from '@/components/ui/HomePreloader'
import httpClient from "@/services/http-client/http-client"

export default {
	name: "Home",
	components: {
		Steps,
		HomePreloader
	},
	data: () => ({
		defaultInit: true,
		keyValue: 0,
		menuItems: null,
		accordionItems: {
			data: [
				{
					id: 1,
					title: "Начало работы с разделом “Товары”",
					child: [
						{
							title: "Создание категории",
							id: 1,
						},
						{
							title: "Создание товара",
							id: 2,
						},
						{
							title: "Создание характеристик товара",
							id: 3
						},
						{
							title: "Создание свойств товара",
							id: 4
						},
						{
							title: "Создание документа закупки",
							id: 5	
						},
						{
							title: "Добавления склада",
							id: 6
						},
						{
							title: "Добавления поставщика",
							id: 7
						},
						{
							title: "Импорт / Экспорт",
							id: 8
						}
					]
				},
				{
					id: 2,
					title: "Начало работы в CRM",
					child: [
						{
							title: "Создание категории",
							id: 1,
						},
						{
							title: "Создание товара",  
							id: 2,
						},
						{
							title: "Создание характеристик товара",
							id: 3
						},
						{
							title: "Создание свойств товара",
							id: 4
						},
						{
							title: "Создание документа закупки",
							id: 5	
						},
						{
							title: "Добавления склада",
							id: 6
						},
						{
							title: "Добавления поставщика",
							id: 7
						},
						{
							title: "Импорт / Экспорт",
							id: 8
						}
					]
				},
				{
					id: 3,
					title: "Работа с документами",
					child: []
				},
				{
					id: 4,
					title: "Управление движением денежных средств",
					child: []
				},
				{
					id: 5,
					title: "Управление маркетингом",
					child: []
				},
				{
					id: 6,
					title: "Формирование отчетов",
					child: []
				},
				{
					id: 7,
					title: "Создание интернет магазина",
					child: []
				},
				{
					id: 8,
					title: "Установка расширений",
					child: []
				},
				{
					id: 9,
					title: "Управление и настройка системы",
					child: []
				},
			]
		},
		timer: null
	}),
	computed: {
		...mapGetters([
			"user",
			"menu"
		]),
		menuList() {
			return this.menu
		},
		checkDomainStore() {
			return localStorage.getItem('domain')
		},
	},
	methods: {
		...mapActions(['fetchSettings']),
		toggle(e) {
			console.log(this.user)
			if(e.target.classList.contains('mainInnerSidebarItems__btn--open')) {
				e.target.classList.remove('mainInnerSidebarItems__btn--open')
				e.target.nextElementSibling.classList.remove('mainInnerSidebarItems__list--open')
				return false
			}
			for(let key in this.$refs) {
				this.$refs[key][0].classList.remove('mainInnerSidebarItems__btn--open')
				this.$refs[key][0].nextElementSibling.classList.remove('mainInnerSidebarItems__list--open')
			}
			e.target.classList.add('mainInnerSidebarItems__btn--open')
			setTimeout(() => e.target.nextElementSibling.classList.add('mainInnerSidebarItems__list--open'), 350)
		},
	},
	created() {},
	async mounted() {
		setTimeout( async () => {
			if(!this.user.detail.onboarding_edit || !this.user.detail.onboarding_tariff) {
				const checkGenerate = await httpClient.get(`/user/company/check_generate`)
				console.log('checkGenerate', checkGenerate.data.success)
				if(!checkGenerate.data.success) {
					this.defaultInit = true
					this.timer = setTimeout(() => {
						this.defaultInit = false
						clearTimeout(this.timer)
					}, 10000)
				} else {
					this.defaultInit = false
				}
			}
			await this.fetchSettings()
		}, 500)
	}
}

</script>

<style lang="sass" scoped>
	.fade-enter-active, .fade-leave-active
		transition: opacity 1s ease-in-out
	.fade-enter, .fade-leave-to
		opacity: 0
</style>