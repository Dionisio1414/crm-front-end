<template>
  <div :class="[!isLeadCard ? isFullWidthCard : '', defaultClassCard]">
    <div class="card-lead__top-row">
      <div class="card-lead__lead-name lead-name">
        <span>Иванов Иван</span>
      </div>
      <div class="loyalty-wrapper">
        <span v-if="!isLeadCard" class="loyalty-text">Лояльность</span>
        <div class="counter-loyalty rating-loyalty-5">
          <div class="counter-loyalty__number">5</div>
        </div>
      </div>
      <button v-if="isLeadCard" class="btn-change-card">
        <SvgSprite
          :width="5"
          :height="19"
          iconId="icon-dots"
        />
      </button>
    </div>
    <div class="event-row">
      <div class="event-row__data">
        <div class="event-row__type-actions">Звонок</div>
        <ul class="data-value">
          <li>14.08.2020</li>
          <li>16:00</li>
        </ul>
      </div>
      <ul class="event-row__actions">
        <li>
          <button>
            <SvgSprite
              :width="18"
              :height="18"
              iconId="icon-phone"
            />
          </button>
        </li>
        <li>
          <button>
            <SvgSprite
                :width="20"
                :height="16"
                iconId="icon-message"
            />
            <span class="missed-data">10</span>
          </button>
        </li>
        <li>
          <button>
            <SvgSprite
                :width="19"
                :height="18"
                iconId="icon-chat"
            />
            <span class="missed-data">5</span>
          </button>
        </li>
      </ul>
    </div>
    <div v-if="isLeadCard" class="card-lead__bottom-block card-lead__bottom-block--preview">
      <div class="price-label-wrapper">Планируемая продажа на: <span class="total-price">9 504 грн</span></div>
      <div class="description">
        <p>Lorem ipsum dolor sit amet, consectetur adipiscing (тут комментарий на две строки)</p>
      </div>
    </div>
    <div v-else class="card-lead__bottom-block card-lead__bottom-block--fluid">
      <div class="price-block">
        <div class="price-label-wrapper"><span class="price-label">Сумма:</span><span class="price">9 504 грн <span class="percent">-10%</span></span></div>
        <div class="price-label-wrapper"><span class="price-label">Итого:</span><span class="total-price">9 504 грн</span></div>
      </div>
      <div class="description">
        <p>Lorem ipsum dolor sit amet, consectetur adipiscing. Sit amet, consectetur adipiscing (тут комментарий на четыре строки)</p>
      </div>
    </div>
  </div>
</template>

<script>
import SvgSprite from "@/components/ui/SvgSprite/SvgSprite"
export default {
  name: "CardPreviewLead",
  props: {
    isLeadCard: Boolean
  },
  components: {
    SvgSprite,
  },
  data() {
    return {
      isFullWidthCard: 'card-lead--full',
      defaultClassCard: 'card-lead'
    }
  }
}
</script>

<style scoped lang="scss">
.card-lead {
  transition: background-color .3s, box-shadow .3s, border .3s;
  background-color: #F4F9FF;
  border-radius: 10px;
  padding: 20px 15px;
  margin: 0 0 10px;
  border: 1px solid transparent;
  cursor: pointer;
  color: #317CCE;

  &:hover,
  &.active-card {
    background-color: #fff;
    border: 1px solid #FFD4A2;
    box-shadow: 0 0 7px rgba(255, 212, 161, 0.5);
  }

  &__top-row {
    display: flex;
    flex-wrap: wrap;
    position: relative;

    .btn-change-card {
      position: absolute;
      right: 0;
      top: 0;
    }
  }

  &--full {
    cursor: default;
    background-color: transparent;
    border-radius: 0;
    margin: 0;
    border: none;

    &:hover,
    &.active-card {
      background-color: transparent;
      border: none;
      box-shadow: none;
    }

    .card-lead__top-row {
      margin: 0 0 9px;
      justify-content: space-between;
    }
  }

  &:last-child {
    margin: 0;
  }

  &__lead-name {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
  }
}

.loyalty-wrapper {
  display: flex;
  flex-wrap: wrap;

  .loyalty-text {
    font-size: 12px;
  }
}

.counter-loyalty {
  width: 22px;
  height: 22px;
  position: relative;
  border-width: 1px;
  border-style: solid;
  border-radius: 50%;
  margin: 0 0 0 10px;

  &__number {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    font-size: 12px;
    line-height: 1;
  }
}

.event-row {
  display: flex;
  flex-wrap: wrap;
  justify-content: space-between;
  align-items: flex-end;
  margin: 0 0 16px;

  &__type-actions {
    font-size: 14px;
    line-height: 1;
    margin: 0 0 9px;
  }

  &__data {
    .data-value {
      display: flex;
      flex-wrap: wrap;
      font-size: 13px;
      letter-spacing: .5px;
      color: #9DCCFF;
      padding: 0;
      margin: 0 -5px;
      list-style: none;

      li {
        padding: 0 5px;
      }
    }
  }

  &__actions {
    display: flex;
    flex-wrap: wrap;
    align-items: flex-end;
    padding: 0;
    margin: 0 -8px;
    list-style: none;

    li {
      padding: 0 8px;
      line-height: 0;

      button {
        line-height: 1;
      }

      .missed-data {
        color: #f00;
        font-size: 12px;
      }
    }
  }
}

.rating-loyalty-1 {
  border-color: #EB5757;
  color: #EB5757;
}

.rating-loyalty-2 {
  border-color: #EB7257;
  color: #EB7257;
}

.rating-loyalty-3 {
  border-color: #F2994A;
  color: #F2994A;
}

.rating-loyalty-4 {
  border-color: #FFB359;
  color: #FFB359;
}

.rating-loyalty-5 {
  border-color: #FFCB13;
  color: #FFCB13;
}

.rating-loyalty-6 {
  border-color: #A4E716;
  color: #A4E716;
}

.rating-loyalty-7 {
  border-color: #50E42B;
  color: #50E42B;
}

.rating-loyalty-8 {
  border-color: #2BE45F;
  color: #2BE45F;
}

.rating-loyalty-9 {
  border-color: #19D04C;
  color: #19D04C;
}

.rating-loyalty-10 {
  border-color: #08C23C;
  color: #08C23C;
}

.card-lead__bottom-block {
  .price-label-wrapper {
    display: flex;
    flex-wrap: wrap;
    line-height: 1;
  }

  .price-label {
    display: inline-block;
    width: 52px;
  }

  .percent {
    font-size: 12px;
    color: #4ECA80;
    margin: 0 0 0 10px;
  }

  .total-price {
    font-weight: 700;
  }

  .description {
    line-height: 18px;

    p {
      margin: 0;
    }
  }

  &--preview {
    .price-label-wrapper {
      font-size: 14px;
      justify-content: space-between;
      padding: 0 0 13px;
      border-bottom: 1px solid #E3F0FF;
      margin: 0 0 10px;
    }

    .total-price {
      font-size: 15px;
    }

    .description {
      font-size: 14px;
    }
  }

  &--fluid {
    display: flex;
    flex-wrap: wrap;

    .card-lead__top-row {
      justify-content: space-between;
    }

    .price-block {
      padding: 20px 15px;
      margin: 0 20px 0 0;
      background-color: #F4F9FF;
      max-width: 330px;
      width: 100%;
      border-radius: 10px;

      .price-label-wrapper {
        font-size: 15px;
        align-items: center;
        color: #5893D4;

        &:first-child {
          border-bottom: 1px solid #E3F0FF;
          padding: 0 0 15px;
          margin: 0 0 15px;
          color: #7E7E7E;
        }
      }

      .total-price,
      .price {
        font-size: 20px;
        margin: 0 0 0 17px;
      }
    }

    .description {
      max-width: 230px;
      font-size: 14px;
    }
  }
}

.lead-name {
  span {
    color: #317CCE;
    font-weight: 500;
    line-height: 20px;
  }
}

</style>